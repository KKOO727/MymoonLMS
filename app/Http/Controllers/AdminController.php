<?php

namespace App\Http\Controllers;

use App\Classes\VideoStream;
use App\Exports\BalanceAdminExport;
use App\Exports\ContentAdminExport;
use App\Exports\DrawAdminExport;
use App\Exports\QuizzesAdminExport;
use App\Exports\QuizzesResultAdminExport;
use App\Models\AdsBox;
use App\Models\AdsPlan;
use App\Models\AdsRequest;
use App\Models\Article;
use App\Models\Balance;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComments;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Channel;
use App\Models\ChannelRequest;
use App\Models\ClassModel;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentCategoryFilter;
use App\Models\ContentCategoryFilterTag;
use App\Models\ContentCategoryFilterTagRelation;
use App\Models\ContentComment;
use App\Models\ContentMeta;
use App\Models\ContentPart;
use App\Models\ContentSupport;
use App\Models\ContentVip;
use App\Models\Discount;
use App\Models\DiscountContent;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\MeetingDate;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Record;
use App\Models\Requests;
use App\Models\Schedule;
use App\Models\Sell;
use App\Models\Social;
use App\Models\Tickets;
use App\Models\TicketsCategory;
use App\Models\TicketsMsg;
use App\Models\TicketsUser;
use App\Models\Transaction;
use App\Models\Usercategories;
use App\Models\Usermeta;
use App\Models\UserRate;
use App\Models\UserRateRelation;
use App\User;
use App\Models\ViewTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    public function __construct()
    {

    }

    public function adminProfile()
    {
        $admin = auth()->user();
        return view('admin.manager.profile', ['user' => $admin]);
    }

    public function adminProfileMainUpdate(Request $request)
    {
        $admin = auth()->user();
        $admin->update($request->all());
        return back();
    }

    public function adminProfileSecurityUpdate(Request $request)
    {
        $admin = auth()->user();
        $password = $request->get('password');
        $re_password = $request->get('re_password');

        if (!empty($password) and !empty($re_password) and $password == $re_password) {
            $new_password = Hash::make($password);
            $admin->update([
                'password' => $new_password
            ]);

            return Redirect::back()->withErrors([trans('admin.password_changed')]);
        }

        return Redirect::back()->withErrors([trans('admin.pass_confirm_alert')]);
    }

    ########################
    #### About Section #####
    ########################
    public function about()
    {
        return view('admin.about');
    }

    ########################
    ## Report & Dashboard ##
    ########################
    public function usersReports()
    {

        $teacherCount = User::where('type','Teacher')->count();
        $studentCount = User::where('type','Student')->count();
        $adminCount = User::where('type', 'admin')->count();

        $oneCount = Content::where('mode', 'publish')->where('type','1')->count();
        $groupCount = Content::where('mode', 'publish')->where('type','2')->count();
        $videoCount = Content::where('mode', 'publish')->where('type','3')->count();


        $sellCount = Sell::count();
        $totalSales = Balance::whereIn('type',['sell','mymoon_charge','service_charge'])->where('status','success')->sum('price');
        $teacherIncome = Balance::where('type','sell')->where('status','success')->sum('price');
        $mymoonIncome = Balance::whereIn('type',['mymoon_charge','service_charge','withdrawal_charge'])->where('status','success')->sum('price');
        $allDeposit = Balance::where('type','deposit')->where('status','success')->sum('price');



        $data = [
            'teacherCount' => $teacherCount,
            'adminCount' => $adminCount,
            'studentCount' => $studentCount,
            'oneCount' => $oneCount,
            'groupCount' => $groupCount,
            'videoCount' => $videoCount,
            'sellCount' => $sellCount,
            'teacherIncome' => $teacherIncome,
            'mymoonIncome' => $mymoonIncome,
            'allDeposit'=>$allDeposit,
            'totalSales'=>$totalSales,
        ];

        return view('admin.report.user', $data);
    }

    public function contentReports()
    {
        $contentCount = Content::where('mode', 'publish')->count();
        $videoCount = ContentPart::where('mode', 'publish')->count();
        $dayRegister = Content::where('created_at', '>', strtotime('-' . get_option('chart_day_count', 10) . ' day') + 12600)->get();
        $videoRegister = ContentPart::where('created_at', '>', strtotime('-' . get_option('chart_day_count', 10) . ' day') + 12600)->get();

        $data = [
            'contentCount' => $contentCount,
            'videoCount' => $videoCount,
            'dayRegister' => $dayRegister,
            'videoRegister' => $videoRegister
        ];

        return view('admin.report.content', $data);
    }

    public function balanceReports()
    {
        $sellCount = Sell::count();
        $transactionCount = Transaction::count();
        $transactionRegistr = Transaction::where('created_at', '>', strtotime('-' . get_option('chart_day_count', 10) . ' day') + 12600)->get();
        $dayRegister = Sell::where('created_at', '>', strtotime('-' . get_option('chart_day_count', 10) . ' day') + 12600)->get();
        $allIncome = Transaction::where('mode', 'deliver')->sum('price');
        $userIncome = Transaction::where('mode', 'deliver')->sum('seller_income');
        $siteIncome = $allIncome - $userIncome;
        $allDeposit = Balance::where('title','TopUp')->where('type','add')->where('status','success')->sum('price');

        $data = [
            'dayRegister' => $dayRegister,
            'transactionRegister' => $transactionRegistr,
            'sellCount' => $sellCount,
            'transactionCount' => $transactionCount,
            'allIncome' => $allIncome,
            'userIncome' => $userIncome,
            'siteIncome' => $siteIncome,
            'allDeposit'=>$allDeposit
        ];

        return view('admin.report.balance', $data);
    }

    #####################
    ###Manager Section###
    #####################
    public function managerLists()
    {
        $userList = User::with('usermetas')->where('type', 'admin')->orderBy('id', 'DESC')->get();
        return view('admin.manager.list', array('users' => $userList));
    }

    public function managerShow($id)
    {
        $user = User::where('id', $id)->first();
        $userMetas = Usermeta::where('user_id', $id)->pluck('value', 'option')->all();
        return view('admin.manager.item', array('user' => $user, 'meta' => $userMetas));
    }

    public function managerCompatibility($id, Request $request)
    {
        $capatibility_array = $request->capatibility;
        Usermeta::updateOrNew($id, ['capatibility' => serialize($capatibility_array)]);
        cache()->forget('user.' . $id);
        cache()->forget('user.' . $id . '.metas.pluck.value');
        cache()->forget('user.' . $id . '.meta');
        cache()->forget('user.' . $id . '.meta.capatibility');

        return Redirect::to(URL::previous() . '#capatibility');
    }

    public function addNewManager()
    {
        return view('admin.manager.new');
    }

    public function storeNewManager(Request $request)
    {
        $duplicateUsername = User::where('username', $request->username)->first();
        $duplicateEmail = User::where('email', $request->email)->first();
        if (!empty($duplicateEmail)) {
            $request->session()->flash('ErrorEmail', 'duplicate');
            return back();
        }
        if (!empty($duplicateUsername)) {
            $request->session()->flash('ErrorUsername', 'duplicate');
            return back();
        }

        if (empty($duplicate)) {
            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->mode = $request->mode;
            $user->type = 'admin';
            if ($user->save())
                return redirect('/admin/manager/item/' . $user->id);
        } else {
            $request->session()->flash('Error', 'duplicate');
            return back();
        }

    }

    ################
    ##Users Section##
    ################
    public function usersLists(Request $request)
    {
        $list = User::where('type', 'Student')->withCount('buys');
        return view('admin.user.list', array('users' => $list->get()));
    }

    public function userShow($id)
    {
        $userCategory = Usercategories::all();

        $user = User::where('id', $id)->first();

        $userMetas = get_all_user_meta($user->id);

        $mrate = UserRateRelation::with('rate')->where('user_id', $id)->get();

        $getrate = getTeacherRating($user->id);

        $lists = UserRate::orderBy('mode')->get();

        return view('admin.user.item', array('user' => $user, 'category' => $userCategory, 'userMetas' => $userMetas, 'lists' => $lists, 'mrates' => $mrate, 'getrate' => $getrate));
    }

    public function userEdit($id, Request $request)
    {

        $request->request->remove('block_date');
        Usermeta::updateOrNew($id, ['blockDate' => toTimestamp($request->blockDate)]);
        $request->request->remove('blockDate');
        cache()->forget('user.' . $id . '.metas.pluck.value');
        cache()->forget('user.' . $id . '.meta');
        cache()->forget('user.' . $id . '.meta.blockDate');
        $uUser = User::find($id);

        ## Notification Center
        if($request->mode == 'active' && $uUser->mode =='pending_manual_verification'){
            $uUser->update($request->all());
            sendMail([
                'template' => get_option('teacher_account_verified'),
                'recipient' => [$uUser->email]
            ]);
        }
        $uUser->update($request->all());
        return back();
    }

    public function userPassword($id){
        $user = User::find($id);
    }

    public function userDelete($id)
    {
        User::where('id', $id)->delete();
        return back();
    }

    public function userEditProfile($id, Request $request)
    {
        $data = $request->except('_token');
        Usermeta::updateOrNew($id, $data);

        cache()->forget('user.' . $id . '.metas.pluck.value');
        cache()->forget('user.' . $id . '.meta');
        foreach ($data as $key => $value) {
            cache()->forget('user.' . $id . '.meta.' . $key);
        }
        return back();
    }

    public function userRateSection($id, Request $request)
    {
        UserRateRelation::updateOrNew($id, [$request->rate]);
        $userRate = UserRate::find($request->rate);

        $userR = User::find($id);
        ## Notification Center
        sendNotification(0, ['[u.name]' => $userR->name, '[u.username]' => $userR->username], get_option('notification_template_get_medal'), 'user', $id);

        if ($userRate->gift > 0) {
            Balance::create([
                'title' => trans('admin.user_rate_badge_gift'),
                'description' => trans('admin.user_rate_new_badge_gift') . $userRate->description,
                'type' => 'add',
                'price' => $userRate->gift,
                'mode' => 'auto',
                'user_id' => $id,
                'exporter_id' => 0,
                'created_at' => time()
            ]);
            $userUp = User::find($id);
            $userUp->update(['credit' => $userUp->credit + $userRate->gift]);
        }
        return redirect(URL::previous() . '#rate');
    }

    public function userRateSectionDelete($id)
    {
        $userRR = UserRateRelation::find($id);
        $userM = UserRate::find($userRR->rate_id);
        $userR = User::find($userRR->user_id);
        ## Notification Center
        sendNotification(0, ['[u.name]' => $userR->name, '[u.username]' => $userR->username, '[u.m.title]' => $userM->description], get_option('notification_template_delete_medal'), 'user', $userR->id);

        $userRR->delete();
        return redirect(URL::previous() . '#rate');
    }

    public function userVendors()
    {
        $list = User::where('type', 'Teacher')->withCount('contents', 'sells', 'buys');

        return view('admin.user.vendors', ['users' => $list->get()]);
    }

    public function userEvent($id)
    {
        $list = Event::where('user_id', $id)->orderBy('id', 'DESC');
        return view('admin.user.event', ['list' => $list->paginate(15)]);
    }

    ## Seller
    public function teacherVerify()
    {
        $users = User::where('mode', 'pending_manual_verification')->get();
        return view('admin.user.teacherVerify', ['users' => $users]);
    }

    ## Category Section ##
    public function userCategory()
    {
        $lists = Usercategories::withCount('users')->get();
        return view('admin.user.categroy', array('lists' => $lists));
    }

    public function userCategoryEdit($id)
    {
        $lists = Usercategories::withCount('users')->get();
        $edit = Usercategories::find($id);
        return view('admin.user.categroyedit', array('lists' => $lists, 'edit' => $edit));
    }

    public function userCategoryStore(Request $request)
    {
        $rules = [
            'title' => 'required',
            'off' => 'required',
            'commision' => 'required',
        ];
        $this->validate($request, $rules);
        Usercategories::create($request->all());
        return back();
    }

    public function userCategoryUpdate($id, Request $request)
    {
        Usercategories::find($id)->update($request->all());
        return back();
    }

    public function userCategoryDelete($id)
    {
        Usercategories::find($id)->delete();
        return back()->with('msg', trans('main.delete'));
    }

    public function userInCategory($id)
    {
        $category = Usercategories::where('id', $id)->first();
        $userList = User::with('category', 'contents', 'sells', 'buys')->where('admin', '0')->where('category_id', $id)->get();
        return view('admin.user.incategory', array('users' => $userList, 'category' => $category));
    }

    ## Rate Section
    public function userRate()
    {
        $lists = UserRate::all();
        return view('admin.user.rate', array('lists' => $lists));
    }

    public function userRateStore(Request $request)
    {
        if ($request->edit == '') {
            $newRate = new UserRate;
            $newRate->description = $request->description;
            $newRate->image = $request->image;
            $newRate->mode = $request->mode;
            $newRate->gift = $request->price;
            $newRate->commision = $request->commision;
            $newRate->value = $request->start . ',' . $request->end;
            $newRate->save();
        } else {
            $rate = UserRate::find($request->edit);
            $rate->description = $request->description;
            $rate->image = $request->image;
            $rate->mode = $request->mode;
            $rate->gift = $request->price;
            $rate->commision = $request->commision;
            $rate->value = $request->start . ',' . $request->end;
            $rate->save();
        }
        return redirect(url()->previous() . '#' . $request->mode);
    }

    public function userRateEdit($id, $tag)
    {
        $lists = UserRate::all();
        $item = UserRate::where('id', $id)->first();
        $item->start = explode(',', $item->value)[0];
        $item->end = explode(',', $item->value)[1];
        return view('admin.user.rate', array('lists' => $lists, 'item' => $item, 'tag' => $tag));
    }

    public function userRateDelete($id, $tag)
    {
        $deleteItem = UserRate::find($id);
        $deleteItem->delete();
        return redirect(url()->previous() . '#' . $tag);
    }

    ## Admin Login Like User
    public function loginWithUser($id, Request $request)
    {
        $admin = (auth()->check()) ? auth()->user() : false;
        if ($admin and $admin->isAdmin()) {
            $user = User::findOrFail($id);

            if ($user->isAdmin()) {
                return redirect('/admin');
            }

            session()->put(['impersonated' => $user->id]);

            Event::create([
                'user_id' => $admin->id,
                'type' => 'Admin Login',
                'ip' => $request->ip()
            ]);

            return redirect('/user');
        }

        abort(404);;
    }


    ####################
    ###Ticket Section###
    ####################
    public function tickets()
    {
        $tickets = Tickets::with('user', 'category')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.ticket.list', ['lists' => $tickets]);
    }

    public function ticketNew()
    {
        $users = User::all();
        $category = TicketsCategory::all();
        return view('admin.ticket.new', ['users' => $users, 'category' => $category]);
    }

    public function ticketNewStore(Request $request)
    {
        $admin = auth()->user();
        $ticket = Tickets::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'created_at' => time(),
            'updated_at' => time(),
            'mode' => 'open',
            'category_id' => $request->category_id
        ]);
        TicketsMsg::create([
            'ticket_id' => $ticket->id,
            'msg' => $request->msg,
            'created_at' => time(),
            'user_id' => $admin->id,
            'mode' => 'admin'
        ]);
        return redirect('admin/ticket/reply/' . $ticket->id);
    }

    public function ticketsOpen(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $lists = Tickets::with('user', 'category')->where('mode', 'open');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));

        $lists = $lists->get();
        $users = User::all();
        return view('admin.ticket.listopen', ['lists' => $lists, 'users' => $users]);
    }

    public function ticketsClose(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $lists = Tickets::with(['user', 'category', 'users' => function ($q) {
            $q->with('user');
        }])->where('mode', '')->orWhere('mode', 'close');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));

        $lists = $lists->get();
        $users = User::all();
        return view('admin.ticket.listclose', ['lists' => $lists, 'users' => $users]);
    }

    public function ticketDelete($id)
    {
        Tickets::find($id)->delete();
        return back();
    }

    public function ticketClose($id)
    {
        Tickets::find($id)->update(['mode' => 'close']);
        return back();
    }

    public function ticketOpen($id)
    {
        Tickets::find($id)->update(['mode' => 'open']);
        return back();
    }

    public function ticketUser($id)
    {
        $ticket = Tickets::find($id);
        $userss = User::all();
        $users = TicketsUser::with('user')->where('ticket_id', $id)->get();
        return view('admin.ticket.user', ['users' => $users, 'ticket' => $ticket, 'userss' => $userss]);
    }

    public function ticketUserStore(Request $request)
    {
        TicketsUser::create($request->all());
        return back();
    }

    public function ticketUserDelete($id)
    {
        TicketsUser::find($id)->delete();
        return back();
    }

    public function ticketReply($id)
    {
        $ticket = Tickets::with(['users' => function ($q) {
            $q->with('user');
        }])->find($id);
        $ticketMsg = TicketsMsg::where('ticket_id', $id)->with('user')->get();

        return view('admin.ticket.reply', ['ticket' => $ticket, 'ticketMsg' => $ticketMsg]);
    }

    public function ticketReplyEdit($tid, $id)
    {
        $ticket = Tickets::find($tid);
        $ticketMsg = TicketsMsg::where('ticket_id', $tid)->with('user')->get();
        $item = TicketsMsg::find($id);
        return view('admin.ticket.reply', ['ticket' => $ticket, 'ticketMsg' => $ticketMsg, 'item' => $item]);
    }

    public function ticketReplyDelete($id)
    {
        $ticket = TicketsMsg::find($id)->delete();
        return back();
    }

    public function ticketStore(Request $request, $id)
    {
        $admin = auth()->user();
        $ticket = Tickets::find($id);
        if (!empty($request->id)) {
            $edit = TicketsMsg::find($request->id);
            $edit->msg = $request->msg;
            $edit->attach = $request->attach;
            $edit->save();
        } else {
            $edit = new TicketsMsg;
            $edit->msg = $request->msg;
            $edit->ticket_id = $id;
            $edit->created_at = time();
            $edit->user_id = $admin->id;
            $edit->attach = $request->attach;
            $edit->mode = 'admin';
            $edit->save();
        }
        $ticket->update(['updated_at' => time()]);
        ## Notification Center
        //sendNotification(0, ['[t.title]' => $ticket->title], get_option('notification_template_ticket_reply'), 'user', $ticket->user_id);

        return back();
    }

    public function ticketsCategory()
    {
        $list = TicketsCategory::withCount('tickets')->get();
        return view('admin.ticket.categroy', array('lists' => $list));
    }

    public function ticketsCategoryEdit($id)
    {
        $list = TicketsCategory::withCount('tickets')->get();
        $item = TicketsCategory::find($id);
        return view('admin.ticket.categroyedit', array('lists' => $list, 'item' => $item));
    }

    public function ticketsCategoryStore(Request $request)
    {
        $rules = [
            'title' => 'required',
        ];
        $this->validate($request, $rules);

        if ($request->edit != '') {
            $category = TicketsCategory::find($request->edit);
            $category->title = $request->title;
            $category->save();
        } else {
            $category = new TicketsCategory;
            $category->title = $request->title;
            $category->save();
        }
        return back();
    }

    public function ticketsCategoryDelete($id)
    {
        TicketsCategory::find($id)->delete();
        return back();
    }


    ##########################
    ###Notification Section###
    ##########################
    public function notificationLists()
    {
        $admin = auth()->user();
        $lists = Notification::with(['user', 'status' => function ($q) use ($admin) {
            $q->where('user_id', $admin->id);
        }])->orderBy('id', 'DESC')->get();
        return view('admin.notification.list', ['lists' => $lists]);
    }

    public function notificationNew()
    {
        $users = User::all();
        $userCategory = Usercategories::all();
        return view('admin.notification.new', ['users' => $users, 'userCategory' => $userCategory]);
    }

    public function notificationListStore(Request $request)
    {
        $admin = auth()->user();
        if (!empty($request->id)) {
            $notification = Notification::find($request->id);
            $notification->updated_at = time();
        } else {
            $notification = new Notification;
            $notification->created_at = time();
        }
        $notification->user_id = $admin->id;
        $notification->title = $request->title;
        $notification->msg = $request->msg;
        $notification->recipent_type = $request->recipent_type;
        switch ($request->recipent_type) {
            case 'userone':
                $notification->recipent_list = $request->recipent_list_user;
                break;
            case 'users':
                $notification->recipent_list = serialize($request->recipent_list_users);
                break;
            case 'category':
                $notification->recipent_list = $request->recipent_list_category;
                break;
            default:
                $notification->recipent_list = '';
        }
        if ($notification->save())
            $request->session()->flash('status', 'success');
        else
            $request->session()->flash('status', 'error');
        return redirect('/admin/notification/edit/' . $notification->id);
    }

    public function notificationEdit($id)
    {
        $users = User::all();
        $userCategory = Usercategories::all();
        $item = Notification::find($id);
        return view('admin.notification.edit', ['users' => $users, 'userCategory' => $userCategory, 'item' => $item]);
    }

    public function notificationDelete($id)
    {
        Notification::find($id)->delete();
        return back();
    }

    public function notificationTemplateLists()
    {
        $lists = NotificationTemplate::orderBy('id', 'DESC')->get();
        return view('admin.notification.templates', array('lists' => $lists));
    }

    public function notificationTemplateNew()
    {
        return view('admin.notification.templateitem');
    }

    public function notificationTemplateShow($id)
    {
        $item = NotificationTemplate::find($id);
        return view('admin.notification.templateitem', array('item' => $item));
    }

    public function notificationTemplateDelete($id)
    {
        NotificationTemplate::find($id)->delete();
        return back();
    }

    public function notificationTemplateEdit(Request $request)
    {
        if ($request->id == '') {
            $template = new NotificationTemplate;
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
            return redirect('/admin/notification/template/item/' . $template->id);
        } else {
            $template = NotificationTemplate::find($request->id);
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
        }
        return back();
    }

    #################################################
    ########### Content ############## Section ######
    #################################################
    public function contentLists(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;

        $users = User::all();
        $category = ContentCategory::get();
        $lists = Content::with(['category', 'user', 'metas' => function ($qm) {
            $qm->get()->pluck('option', 'value');
        }, 'transactions' => function ($q) {
            $q->where('mode', 'deliver');
        }])->withCount('sells', 'partsactive')->where(function ($w) {
            $w->where('mode', 'publish');
        });


        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('cat', null) !== null)
            $lists->where('category_id', $request->get('cat', null));
        if ($request->get('id', null) !== null)
            $lists->where('id', $request->get('id', null));
        if ($request->get('title', null) !== null)
            $lists->where('title', 'like', '%' . $request->get('title', null) . '%');


        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count', 'DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view', 'DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        } else
            $lists->orderBy('id', 'DESC');


        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        $lists = $lists->paginate(10);

        $data = [
            'lists' => $lists,
            'users' => $users,
            'category' => $category
        ];

        return view('admin.content.list', $data);
    }

    public function contentWaitingList(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category', 'user', 'metas' => function ($qm) {
            $qm->get()->pluck('option', 'value');
        }, 'transactions' => function ($q) {
            $q->where('mode', 'deliver');
        }])->withCount('sells', 'partsactive', 'partsRequest')->where(function ($w) {
            $w->where('mode', 'delete')->orWhere('mode', 'request');
        });

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('cat', null) !== null)
            $lists->whereHas('categories', function ($qu, $request) {
                $qu->where('category_id', $request->get('cat', null));
            });
        if ($request->get('id', null) !== null)
            $lists->where('id', $request->get('id', null));
        if ($request->get('title', null) !== null)
            $lists->where('title', 'like', '%' . $request->get('title', null) . '%');


        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count', 'DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view', 'DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        } else
            $lists->orderBy('id', 'DESC');

        $lists = $lists->paginate(15);

        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        return view('admin.content.waiting', ['lists' => $lists, 'users' => $users, 'category' => $category]);
    }

    public function contentDraftList(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category', 'user', 'metas' => function ($qm) {
            $qm->get()->pluck('option', 'value');
        }, 'transactions' => function ($q) {
            $q->where('mode', 'deliver');
        }])->withCount('sells', 'partsactive')->where('mode', 'draft');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('cat', null) !== null)
            $lists->whereHas('categories', function ($qu, $request) {
                $qu->where('category_id', $request->get('cat', null));
            });
        if ($request->get('id', null) !== null)
            $lists->where('id', $request->get('id', null));
        if ($request->get('title', null) !== null)
            $lists->where('title', 'like', '%' . $request->get('title', null) . '%');


        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count', 'DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view', 'DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        } else {
            $lists->orderBy('id', 'DESC');
        }

        $lists = $lists->paginate(15);

        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        $data = [
            'lists' => $lists,
            'users' => $users,
            'category' => $category,
            'mode' => 'draft'
        ];

        return view('admin.content.list', $data);
    }

    public function contentUserContent(Request $request, $id)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category', 'user', 'metas' => function ($qm) {
            $qm->get()->pluck('option', 'value');
        }, 'transactions' => function ($q) {
            $q->where('mode', 'deliver');
        }])->withCount('sells', 'partsactive')->where('user_id', $id);

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('cat', null) !== null)
            $lists->whereHas('categories', function ($qu) use ($request) {
                $qu->where('category_id', $request->get('cat', null));
            });
        if ($request->get('id', null) !== null)
            $lists->where('id', $request->get('id', null));
        if ($request->get('title', null) !== null)
            $lists->where('title', 'like', '%' . $request->get('title', null) . '%');


        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count', 'DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view', 'DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        } else
            $lists->orderBy('id', 'DESC');

        $lists = $lists->paginate(30);

        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        $data = [
            'lists' => $lists,
            'users' => $users,
            'category' => $category,
        ];

        return view('admin.content.list', $data);
    }

    public function contentEdit($id)
    {
        $item = Content::with(['parts', 'metas', 'tags' => function ($tq) {
            $tq->pluck('tag_id');
        }, 'category' => function ($q) {
            $q->with(['filters' => function ($qs) {
                $qs->with('tags');
            }]);
        }])->find($id);
        $meta = arrayToList($item->metas, 'option', 'value');
        $contentMenu = [];
        $filters = ContentCategoryFilter::where('category_id', $item->category_id)->with('tags')->get();
        $products = Content::where('mode', 'publish')->get();
        $categories = ContentCategory::orderBy('id','ASC')->get();
        return view('admin.content.edit', ['categories'=>$categories,'item' => $item, 'meta' => $meta, 'menus' => $contentMenu, 'filters' => $filters, 'products' => $products]);
    }

    public function contentDelete($id)
    {
        Content::find($id)->delete();
        $schedule_delete=Schedule::where('course_id',$id)->delete();
        contentCacheForget($id);
        return back();
    }

    public function contentStore(Request $request, $id, $mode)
    {
        contentCacheForget($id);
        if ($mode == 'subscribe') {
            Content::find($id)->update($request->all());
            return Redirect::to(URL::previous() . '#subscribe');
        }
        if ($mode == 'main') {
            $admin = auth()->user();
            $content = Content::with('user')->find($id);

            ## Notification Center
            if ($request->mode == 'publish')
                sendNotification(0, ['[u.name]' => $content->user->name, '[c.title]' => $content->title], get_option('notification_template_content_publish'), 'user', $content->user->id);
            if ($request->mode == 'waiting')
                sendNotification(0, ['[u.name]' => $content->user->name, '[c.title]' => $content->title], get_option('notification_template_content_change'), 'user', $content->user->id);


            $content->update($request->except('files'));
            return Redirect::to(URL::previous() . '#main');
        }
        if ($mode == 'meta') {
            $request = $request->all();

            if (isset($request['precourse']))
                $request['precourse'] = implode(',', $request['precourse']) . ',';

            foreach ($request as $key => $val) {
                $res = ContentMeta::updateOrCreate(
                    ['content_id' => $id, 'option' => $key],
                    ['value' => $val]
                );
            }
            return Redirect::to(URL::previous() . '#meta');
        }
        if ($mode == 'tags') {
            ContentCategoryFilterTagRelation::where('content_id', $id)->delete();
            if ($request->tags != null) {
                foreach ($request->tags as $tag) {
                    ContentCategoryFilterTagRelation::create(['content_id' => $id, 'tag_id' => $tag]);
                }
            }
            return Redirect::to(URL::previous() . '#filter');
        }
    }

    public function contentListExcel(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;
        $lists = Content::with(['category', 'user', 'metas' => function ($qm) {
            $qm->get()->pluck('option', 'value');
        }, 'transactions' => function ($q) {
            $q->where('mode', 'deliver');
        }])->withCount('sells', 'partsactive');

        if (!empty($request->get('mode', null))) {
            $lists->where('mode', $request->get('mode'));
        }

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('cat', null) !== null)
            $lists->where('category_id', $request->get('cat', null));
        if ($request->get('id', null) !== null)
            $lists->where('id', $request->get('id', null));
        if ($request->get('title', null) !== null)
            $lists->where('title', 'like', '%' . $request->get('title', null) . '%');
        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count', 'DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view', 'DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        } else {
            $lists->orderBy('id', 'DESC');
        }


        $lists = $lists->get();

        if ($request->get('order', null) != null) {
            switch ($request->get('order', null)) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        return Excel::download(new ContentAdminExport($lists), trans('admin.courses') . '.xlsx');
    }

    public function contentComments()
    {
        $comments = ContentComment::with('content')->orderBy('id', 'DESC')->get();
        return view('admin.content.comments', ['comments' => $comments]);
    }

    public function contentCommentEdit($id)
    {
        $item = ContentComment::find($id);
        return view('admin.content.commentedit', ['item' => $item]);
    }

    public function contentCommentDelete($id)
    {
        ContentComment::find($id)->delete();
        return back();
    }

    public function contentCommentView($action, $id)
    {
        $comment = ContentComment::with('content.user')->find($id);
        $comment->mode = $action;
        $comment->save();
        ContentComment::where('parent', $id)->update(['mode' => $action]);

        ## Notification Center
        if ($action == 'publish')
            if (!empty($comment->content)) {
                sendNotification(0, ['[c.title]' => $comment->content->title], get_option('notification_template_content_comment_new'), 'user', $comment->content->user->id);
            }

        return back();
    }

    public function contentCommentStore(Request $request)
    {
        $comment = ContentComment::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }

    public function contentSupports()
    {
        $comments = ContentSupport::with('content')->orderBy('id', 'DESC')->get();
        return view('admin.content.supports', ['comments' => $comments]);
    }

    public function contentSupportEdit($id)
    {
        $item = ContentSupport::find($id);
        return view('admin.content.supportedit', ['item' => $item]);
    }

    public function contentSupportDelete($id)
    {
        ContentSupport::find($id)->delete();
        return back();
    }

    public function contentSupportView($action, $id)
    {
        $comment = ContentSupport::with('content.user')->find($id);
        $comment->mode = $action;
        $comment->save();

        ## Notification Center
        if ($action == 'publish' and !empty($comment->content)) {
            sendNotification(0, ['[c.title]' => $comment->content->title], get_option('notification_template_content_support_new'), 'user', $comment->content->user->id);
        }

        return back();
    }

    public function contentSupportStore(Request $request)
    {
        $comment = ContentSupport::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }

    public function contentPartEdit($id, $pid)
    {
        $item = Content::with('parts', 'metas', 'category')->where('id', $id)->first();
        $meta = arrayToList($item->metas, 'option', 'value');
        $contentMenu = [];
        $part = ContentPart::find($pid);
        $products = Content::where('mode', 'publish')->get();
        $categories = ContentCategory::get();

        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = 'source/content-' . $id . '/video/part-' . $pid . '.mp4';

        if (file_exists($storagePath . $file))
            $convert = $storagePath . $file;
        else
            $convert = false;

        return view('admin.content.partedit', ['item' => $item,'categories'=>$categories, 'meta' => $meta, 'menus' => $contentMenu, 'part' => $part, 'convert' => $convert, 'products' => $products]);
    }

    public function contentPartStore($id, Request $request)
    {
        ContentPart::find($id)->update($request->except('files'));
        return Redirect::to(URL::previous() . '#part');
    }

    public function contentPartDelete($id)
    {
        ContentPart::find($id)->delete();
        return Redirect::to(URL::previous() . '#parts');
    }

    public function contentUsage($id, Request $request)
    {
        $list = \App\Models\Usage::with('user')
            ->where('product_id', $id)
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->get();

        return view('admin.content.usage', ['list' => $list]);
    }

    public function contentCategory()
    {
        $list = ContentCategory::withCount('contents', 'childs', 'filters')->where('parent_id', '0')->get();
        return view('admin.content.categroy', array('lists' => $list));
    }

    public function contentCategoryEdit($id)
    {
        $list = ContentCategory::withCount('contents', 'childs')->where('parent_id', '0')->get();
        $item = ContentCategory::find($id);
        return view('admin.content.categroyedit', array('lists' => $list, 'item' => $item));
    }

    public function contentCategoryStore(Request $request)
    {
        if ($request->edit != '') {
            $category = ContentCategory::find($request->edit);
            $category->title = $request->title;
            $category->image = $request->image;
            $category->class = $request->class;
            $category->req_icon = $request->req_icon;
            $category->commision = $request->commision;
            $category->parent_id = $request->parent_id;
            $category->color = $request->color;
            $category->background = $request->background;
            $category->icon = $request->icon;
            $category->app_icon = $request->app_icon;
            $category->save();

            cache()->forget('menu.' . $category->parent_id . '.sub');
        } else {
            $category = new ContentCategory;
            $category->title = $request->title;
            $category->image = $request->image;
            $category->commision = $request->commision;
            $category->parent_id = $request->parent_id;
            $category->class = $request->class;
            $category->color = $request->color;
            $category->background = $request->background;
            $category->req_icon = $request->req_icon;
            $category->icon = $request->icon;
            $category->save();

            cache()->forget('menu.parents');
            if (!empty($category->parent_id)) {
                cache()->forget('menu.' . $category->parent_id . '.sub');
            }
        }
        return back();
    }

    public function contentCategoryDelete($id)
    {
        ContentCategory::find($id)->delete();
        return back();
    }

    public function contentCategoryChilds($id)
    {
        $list = ContentCategory::withCount('contents', 'filters')->where('parent_id', $id)->get();
        $item = ContentCategory::find($id);
        return view('admin.content.categroychild', array('lists' => $list, 'item' => $item));
    }

    public function contentCategoryFilter($id)
    {
        $filters = ContentCategoryFilter::withCount('tags')->where('category_id', $id)->orderBy('sort')->get();
        return view('admin.content.filters', ['lists' => $filters, 'id' => $id]);
    }

    public function contentCategoryFilterEdit($id, $fid)
    {
        $filters = ContentCategoryFilter::where('category_id', $id)->orderBy('sort')->get();
        $item = ContentCategoryFilter::find($fid);
        return view('admin.content.filtersedit', ['lists' => $filters, 'id' => $id, 'item' => $item]);
    }

    public function contentCategoryFilterStore(Request $request, $mode)
    {
        if ($mode == 'new')
            ContentCategoryFilter::create($request->all());
        if ($mode == 'edit')
            ContentCategoryFilter::find($request->id)->update(['filter' => $request->filter, 'sort' => $request->sort]);
        return back();
    }

    public function contentCategoryFilterDelete($id)
    {
        ContentCategoryFilter::find($id)->delete();
        return back();
    }

    public function contentCategoryFilterTags($id)
    {
        $filter = ContentCategoryFilter::find($id);
        $tags = ContentCategoryFilterTag::where('filter_id', $id)->orderBy('sort')->get();
        return view('admin.content.tags', ['lists' => $tags, 'filter' => $filter, 'id' => $id]);
    }

    public function contentCategoryFilterTagNew(Request $request, $mode)
    {
        if ($mode == 'new')
            ContentCategoryFilterTag::insert($request->all());
        if ($mode == 'edit') {
            ContentCategoryFilterTag::find($request->id)->update(['tag' => $request->tag, 'sort' => $request->sort]);
        }
        return back();
    }

    public function contentCategoryFilterTagEdit($id, $fid)
    {
        $filter = ContentCategoryFilter::find($id);
        $tags = ContentCategoryFilterTag::where('filter_id', $id)->orderBy('sort')->get();
        $item = ContentCategoryFilterTag::find($fid);
        return view('admin.content.tagsedit', ['filter' => $filter, 'lists' => $tags, 'id' => $id, 'item' => $item]);
    }

    public function contentCategoryFilterTagDelete($id)
    {
        ContentCategoryFilterTag::find($id)->delete();
        return back();
    }


    #######################
    ### Request Section ###
    #######################
    public function requestLists(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $category = ContentCategory::all();

        $lists = Requests::with('user', 'category')->withCount('fans')->orderBy('id', 'DESC');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('cat', null) !== null)
            $lists->where('category_id', $request->get('cat', null));

        $lists = $lists->get();

        return view('admin.request.list', ['lists' => $lists, 'category' => $category]);
    }

    public function requestDelete($id)
    {
        Requests::find($id)->delete();
        return back();
    }

    public function requestDraft($id)
    {
        $request = Requests::find($id);

        ## Notification Center
        sendNotification(0, ['[r.title]' => $request->title], get_option('notification_template_request_draft'), 'user', $request->requester_id);

        $request->update(['mode' => 'draft']);
        return back();
    }

    public function requestPublish($id)
    {
        $request = Requests::with('requester')->find($id);

        ## Notification Center
        sendNotification(0, ['[r.title]' => $request->title], get_option('notification_template_request_publish'), 'user', $request->requester_id);

        if ($request->user_id != '' || $request->user_id != 0) {
            sendNotification(0, ['[r.title]' => $request->title, '[u.name]' => $request->requester->name], get_option('notification_template_request_req'), 'user', $request->user_id);
        }

        $request->update(['mode' => 'publish']);
        return back();
    }

    public function requestRecordList(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $category = ContentCategory::all();

        $lists = Record::with('user', 'category')->withCount('fans')->orderBy('id', 'DESC');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('cat', null) !== null)
            $lists->where('category_id', $request->get('cat', null));

        $lists = $lists->get();

        return view('admin.request.recordlist', ['lists' => $lists, 'category' => $category]);
    }

    public function requestRecordDelete($id)
    {
        Record::find($id)->delete();
        return back();
    }

    public function requestRecordDraft($id)
    {
        Record::find($id)->update(['mode' => 'draft']);
        return back();
    }

    public function requestRecordPublish($id)
    {
        Record::find($id)->update(['mode' => 'publish']);
        return back();
    }


    ##################
    ###Blog Section###
    ##################
    public function blogPosts()
    {
        $postList = Blog::with('comments', 'user')->orderBy('id', 'DESC')->get();
        return view('admin.blog.list', array('posts' => $postList));
    }

    public function blogNewPost()
    {
        $category = BlogCategory::all();
        return view('admin.blog.new', ['category' => $category]);
    }

    public function blogStore(Request $request)
    {
        $admin = auth()->user();

        $request->request->add(['user_id' => $admin->id]);

        if ($request->id) {
            if (isset($request->comment)) {
                $request->request->set('comment', 'enable');
            } else {
                $request->request->set('comment', 'disable');
            }

            $data = $request->except(['_token', 'files']);
            Blog::where('id', $request->id)
                ->update($data);

            cache()->forget('blog.index');
            cache()->forget('blog.home');
            cache()->forget('blog.' . $request->id);

            return back();
        } else {
            $request->request->add(['created_at' => time()]);
            if (isset($request->comment)) {
                $request->request->set('comment', 'enable');
            } else {
                $request->request->set('comment', 'disable');
            }

            $data = $request->except(['_token', 'files']);

            $post = Blog::create($data);

            cache()->forget('blog.index');
            cache()->forget('blog.home');

            return redirect('/admin/blog/post/edit/' . $post->id);
        }
    }

    public function blogEditPost($id)
    {
        $category = BlogCategory::all();
        $item = Blog::find($id);
        return view('admin.blog.edit', ['category' => $category, 'item' => $item]);
    }

    public function blogPostDelete($id)
    {
        Blog::find($id)->delete();
        cache()->forget('blog.index');
        cache()->forget('blog.home');
        cache()->forget('blog.' . $id);

        return back();
    }

    public function blogCategory()
    {
        $list = BlogCategory::withCount('posts')->get();
        return view('admin.blog.categroy', array('lists' => $list));
    }

    public function blogCategoryStore(Request $request)
    {

        if ($request->edit != '') {
            $category = BlogCategory::find($request->edit);
            $category->update(['title' => $request->title]);
        } else {
            $category = new BlogCategory;
            $category->title = $request->title;
            $category->save();
        }
        return back();
    }

    public function blogCategoryEdit($id)
    {
        $list = BlogCategory::all();
        $item = BlogCategory::find($id);
        return view('admin.blog.categroyedit', array('lists' => $list, 'item' => $item));
    }

    public function blogCategoryDelete($id)
    {
        BlogCategory::find($id)->delete();
        return back();
    }

    public function blogComments()
    {
        $comments = BlogComments::with('user', 'post')->orderBy('id', 'DESC')->get();
        return view('admin.blog.comments', ['comments' => $comments]);
    }

    public function blogCommentView($action, $id)
    {
        $comment = BlogComments::find($id);
        $comment->mode = $action;
        $comment->save();
        return back();
    }

    public function blogCommentStore(Request $request)
    {
        $comment = BlogComments::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }

    public function blogCommentEdit($id)
    {
        $item = BlogComments::find($id);
        return view('admin.blog.commentedit', ['item' => $item]);
    }

    public function blogCommentDelete($id)
    {
        BlogComments::find($id)->delete();
        return back();
    }

    public function blogCommentReply($id)
    {
        $item = BlogComments::find($id);
        return view('admin.blog.reply', ['item' => $item]);
    }

    public function blogCommentReplyStore(Request $request)
    {
        $admin = auth()->user();
        $comment = BlogComments::create([
            'comment' => $request->comment,
            'user_id' => $admin->id,
            'created_at' => time(),
            'name' => $admin->name,
            'post_id' => $request->post_id,
            'parent' => $request->parent,
            'mode' => 'publish'
        ]);
        return redirect('admin/blog/comment/edit/' . $comment->id);
    }

    public function articles()
    {
        $postList = Article::with(['user', 'category'])->orderBy('id', 'DESC')->get();
        return view('admin.blog.articlelist', array('posts' => $postList));
    }

    public function articleStore(Request $request, $id)
    {
        Article::find($id)->update($request->except('files'));
        cache()->forget('articles');
        cache()->forget('articles.home');
        cache()->forget('articles.' . $id);
        return back();
    }

    public function articleEdit($id)
    {
        $article    = Article::find($id);
        $categories = ContentCategory::get();
        return view('admin.blog.articleedit', ['item' => $article,'categories'=>$categories]);
    }

    public function articleDelete($id)
    {
        $article = Article::find($id);
        $article->delete();
        cache()->forget('articles');
        cache()->forget('articles.home');
        cache()->forget('articles.' . $id);
        return back();
    }

    #####################
    ## Channel Section ##
    #####################
    public function channelLists()
    {
        $channels = Channel::with(['user'])->withCount(['contents'])->orderBy('id', 'DESC')->get();
        return view('admin.user.channels', ['channels' => $channels]);
    }

    public function channelStore($id, Request $request)
    {
        $channel = Channel::find($id);
        $channel->update($request->except('files'));
        cache()->forget('new.channels');
        cache()->forget('view.channels');
        cache()->forget('popular.channels');
        return back();
    }

    public function channelEdit($id)
    {
        $item = Channel::find($id);
        cache()->forget('channel.username.' . $item->username);
        return view('admin.user.channeledit', ['edit' => $item]);
    }

    public function channelDelete($id)
    {
        $channel = Channel::where('id', $id)->first();
        cache()->forget('channel.username.' . $channel->username);
        $channel->delete();
        return back();
    }

    public function channelExcel()
    {
        $lists = Channel::with(['user'])->withCount(['contents' => function ($q) {
            $q->where('option', 'channel');
        }])->get();
        Excel::create(trans('admin.channels_list'), function ($excel) use ($lists) {
            $excel->sheet('Sheetname', function ($sheet) use ($lists) {
                $sheet->freezeFirstRow();
                $sheet->setAutoSize(true);
                $sheet->cell('A1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setBackground('#FFAB25');
                });

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Tahoma',
                        'size' => 12,
                        'text-align' => 'center'
                    )));
                $sheet->appendRow(array(
                    trans('admin.channel_title'),
                    trans('admin.channel_id'),
                    trans('admin.creator'),
                    trans('admin.verification_status'),
                    trans('admin.contents'),
                    trans('admin.th_status'),
                    trans('admin.views'),
                ));
                foreach ($lists as $item) {
                    if ($item->formal == 'ok')
                        $formal = trans('admin.verified');
                    else
                        $formal = trans('admin.not_verified');

                    if ($item->mode == 'active')
                        $mode = trans('admin.active');
                    else
                        $mode = trans('admin.disabled');


                    $sheet->appendRow(array(
                        $item->title,
                        $item->username,
                        $item->user->username,
                        $formal,
                        $item->contents_count,
                        $mode,
                        $item->view
                    ));
                }
            });
        })->download('xls');
    }

    public function channelRequest()
    {
        $lists = ChannelRequest::orderBy('id', 'DESC')->get();
        return view('admin.user.channelrequest', ['lists' => $lists]);
    }

    public function channelRequestDelete($id)
    {
        ChannelRequest::find($id)->delete();
        return back();
    }

    public function channelRequestDraft($id)
    {
        ChannelRequest::find($id)->update(['mode' => 'draft']);
        return back();
    }

    public function channelRequestPublish($id)
    {
        ChannelRequest::find($id)->update(['mode' => 'publish']);
        return back();
    }

    ##################
    ## Sell Section ##
    ##################
    public function shoppingList(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $lists = Sell::with('user', 'buyer', 'content', 'transaction')->orderBy('id', 'DESC');

        if ($fdate > 12601)
            $lists->where('created_at', '>', $fdate);
        if ($ldate > 12601)
            $lists->where('created_at', '<', $ldate);
        if ($request->get('user', null) !== null)
            $lists->where('user_id', $request->get('user', null));
        if ($request->get('buyer', null) !== null)
            $lists->where('buyer_id', $request->get('buyer', null));
        if ($request->get('content', null) !== null)
            $lists->where('content_id', $request->get('content', null));
        if ($request->get('type', null) !== null) {
            switch ($request->get('type', null)) {
                case 'download':
                    $lists->where('type', 'download');
                    break;
                case 'post':
                    $lists->where('type', 'post');
                    break;
                case 'success':
                    $lists->where('post_feedback', '1');
                    break;
                case 'fail':
                    $lists->where('post_feedback', '2')->orWhere('post_feedback', '3');
                    break;
                case 'wait':
                    $lists->where('post_feedback', null)->where('type', 'post');
                    break;
            }
        }

        $contents = Content::all();
        $users = User::all();
        $lists = $lists->get();
        return view('admin.sell.sell', ['lists' => $lists, 'contents' => $contents, 'users' => $users]);
    }


    #####################
    ## Balance Section ##
    #####################
    public function balanceLists()
    {
        $lists = Balance::with('exporter', 'user')->orderBy('id', 'DESC')->get();
        return view('admin.balance.list', ['lists' => $lists]);
    }

    public function balanceCreate()
    {
        $users = User::all();
        return view('admin.balance.new', ['users' => $users]);
    }

    public function balanceStore(Request $request)
    {
        $admin = auth()->user();
        $request->request->add(['created_at' => time(), 'exporter_id' => $admin->id, 'mode' => 'user']);
        $request->request->remove('date');
        $request->request->remove('fdate');
        $request->request->remove('time');
        $balance = Balance::create($request->all());

        if (!empty($request->get('user_id'))) {
            $userUp = User::find($request->get('user_id'));
            if ($request->get('type') == 'add') {
                if ($request->get('account') == 'credit')
                    $userUp->update(['credit' => $userUp->credit + $request->get('price')]);
                else
                    $userUp->update(['seller_income' => $userUp->income + $request->get('price')]);
                ## Notification Center
                sendNotification(0, ['[b.amount]' => $request->get('price'), '[b.description]' => $request->get('description'), '[b.type]' => 'Add'], get_option('notification_template_withdraw_new'), 'user', $request->get('user_id'));
            } else {
                if ($request->get('account') == 'credit')
                    $userUp->update(['credit' => $userUp->credit - $request->get('price')]);
                else
                    $userUp->update(['seller_income' => $userUp->income - $request->get('price')]);
                ## Notification Center
                sendNotification(0, ['[b.amount]' => $request->get('price'), '[b.description]' => $request->get('description'), '[b.type]' => 'کسر'], get_option('notification_template_withdraw_new'), 'user', $request->get('user_id'));
            }
        }
        return redirect('/admin/balance/list');
    }

    public function balanceEdit($id)
    {
        $balance = Balance::find($id);

        if (!$balance) {
            abort(404);
        }

        $users = User::all();
        return view('admin.balance.edit', ['item' => $balance, 'users' => $users]);
    }

    public function balanceUpdate($id, Request $request)
    {
        $admin = auth()->user();
        $request->request->add(['created_at' => strtotime($request->date) + timeToSecond($request->time) - 12600, 'exporter_id' => $admin->id, 'mode' => 'user']);
        $request->request->remove('date');
        $request->request->remove('fdate');
        $request->request->remove('time');
        Balance::find($id)->update($request->all());
        if (isset($request->user_id)) {
            $userUp = User::find($request->user_id);
            if ($request->type == 'add')
                $userUp->update(['credit' => $userUp->credit + $request->price]);
            else
                $userUp->update(['credit' => $userUp->credit - $request->price]);
        }
        return back();
    }

    public function balanceListsExcel(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null)) + 12600;
        $ldate = strtotime($request->get('ldate', null)) + 12600;

        if ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null) {
            $lists = Balance::with('exporter', 'user')->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->orderBy('id', 'DESC')->get();
        } elseif ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null && $request->get('user_id', null) !== null) {
            $lists = Balance::with('exporter', 'user')->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('user_id', $request->get('user_id', null))->orderBy('id', 'DESC')->get();
        } elseif ($request->get('fdate', null) == null && $request->get('ldate', null) == null && $request->get('user_id', null) !== null) {
            $lists = Balance::with('exporter', 'user')->where('user_id', $request->get('user_id', null))->orderBy('id', 'DESC')->get();
        } else {
            $lists = Balance::with('exporter', 'user')->orderBy('id', 'DESC')->get();
        }


        return Excel::download(new BalanceAdminExport($lists), trans('admin.financial_documents') . '.xlsx');
    }

    public function balanceDelete($id)
    {
        Balance::find($id)->delete();
        return back();
    }

    public function balanceWithdraw(Request $request)
    {
        $withdrawals = Balance::where('type','withdrawal')->get();

        return view('admin.balance.withdraw', ['withdrawals' => $withdrawals]);
    }

    public function confirmWithdraw(Request $request)
    {

        $withdrawal = Balance::where('id',$request->id)->where('type','withdrawal')->where('status','pending')->get();
        if($withdrawal->count() == 1){
            $req_id=$withdrawal[0]->description;

            $update_withdrawal = Balance::where('id',$request->id)->where('type','withdrawal')->where('status','pending')
                ->update(['status'=>$request->status,'token'=>$request->details]);
            $update_withdrawal_charge = Balance::where('description',$req_id)->where('type','withdrawal_charge')->where('status','pending')
                ->update(['status'=>$request->status,'token'=>$request->details]);

            $user=User::find($withdrawal[0]->user_id);
            ## Notification Center
            if($request->status == 'success'){
                sendNotification(0, ['[b.amount]' => $withdrawal[0]->price, '[b.description]' => 'Your withdrawal request has been processed successfully.Transaction Details: '.$request->details], get_option('notification_template_withdraw_pay'), 'user', $user->id);
            }else{
                sendNotification(0, ['[b.amount]' => $withdrawal[0]->price, '[b.description]' => 'Your withdrawal request has been declined. Reason: '.$request->details], get_option('notification_template_withdraw_pay'), 'user', $user->id);

            }
        }

        return redirect('admin/balance/withdraw');
    }

    public function balanceWithdrawAll(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null));
        $ldate = strtotime($request->get('ldate', null));
        $users = User::with(['usermetas', 'sells' => function ($q) {
            $q->where('mode', 'pay')->where('type', 'post')->where('post_confirm', null);
        }]);
        if ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null)
            $users->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('seller_income', '>=', get_option('site_withdraw_price', 0))->orderBy('id', 'DESC');
        elseif ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null && $request->get('price', null) !== null)
            $users->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('seller_income', '>=', $request->get('price', null))->orderBy('id', 'DESC');
        elseif ($request->get('fdate', null) == null && $request->get('ldate', null) == null && $request->get('user_id', null) !== null)
            $users->where('seller_income', '>=', $request->get('price', null))->orderBy('id', 'DESC');
        else
            $users->where('seller_income', '>=', get_option('site_withdraw_price', 0))->orderBy('id', 'DESC');

        $users = $users->get();
        foreach ($users as $index => $wuser) {
            $seller_apply = userMeta($wuser->id, 'seller_apply', 0);
            if ($seller_apply == 0)
                $users->forget($index);
            if (count($wuser->sells) > 0)
                $users->forget($index);
        }

        $admin = auth()->user();
        foreach ($users as $user) {
            Balance::insert([
                'title' => trans('admin.withdraw_period'),
                'type' => 'minus',
                'price' => $user->income,
                'mode' => 'user',
                'user_id' => $user->id,
                'description' => $request->description,
                'exporter_id' => $admin->id,
                'created_at' => time()
            ]);
            User::find($user->id)->update(['income' => 0]);
            ## Notification Center
            sendNotification(0, ['[b.amount]' => $user->income, '[b.description]' => $request->description], get_option('notification_template_withdraw_pay'), 'user', $user->id);
        }

        return back();
    }

    public function balanceWithdrawExcel(Request $request)
    {
        $fdate = strtotime($request->get('fdate', null));
        $ldate = strtotime($request->get('ldate', null));
        $users = User::with(['usermetas', 'sells' => function ($q) {
            $q->where('mode', 'pay')->where('type', 'post')->where('post_confirm', '');
        }]);
        if ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null)
            $users->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('income', '>=', get_option('site_withdraw_price', 0))->orderBy('id', 'DESC');
        elseif ($request->get('fdate', null) !== null && $request->get('ldate', null) !== null && $request->get('price', null) !== null)
            $users->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('income', '>=', $request->get('price', null))->orderBy('id', 'DESC');
        elseif ($request->get('fdate', null) == null && $request->get('ldate', null) == null && $request->get('user_id', null) !== null)
            $users->where('income', '>=', $request->get('price', null))->orderBy('id', 'DESC');
        else
            $users->where('income', '>=', get_option('site_withdraw_price', 0))->orderBy('id', 'DESC');

        $users = $users->get();
        foreach ($users as $index => $wuser) {
            $seller_apply = userMeta($wuser->id, 'seller_apply', 0);
            if ($seller_apply == 0)
                $users->forget($index);
            if (count($wuser->sells) > 0)
                $users->forget($index);
        }

        $allsum = $users->sum('income');

        return Excel::download(new DrawAdminExport($users, $allsum), trans('admin.withdrawal_list') . '.xlsx');
    }

    public function balanceAnalyze(Request $request)
    {
        $users = User::all();
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));

        $lists = Balance::with('exporter', 'user')->where(function ($q) {
            $q->where('user_id', '')
                ->orwhere('user_id', 0)
                ->orWhere('user_id', null);
        });

        if ($request->get('fsdate', null) !== null && $request->get('lsdate', null) !== null)
            $lists->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->orderBy('id', 'DESC');
        elseif ($request->get('fsdate', null) !== null && $request->get('lsdate', null) !== null && $request->get('user_id', null) !== null)
            $lists->where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->where('user_id', $request->get('user_id', null))->orderBy('id', 'DESC');
        elseif ($request->get('fsdate', null) == null && $request->get('lsdate', null) == null && $request->get('user_id', null) !== null)
            $lists->where('user_id', $request->get('user_id', null))->orderBy('id', 'DESC');
        else
            $lists->orderBy('id', 'DESC');

        $lists = $lists->get();
        $first = $lists->first();
        $last = $lists->last();
        $alladd = $lists->where('type', 'add')->sum('price');
        $allminus = $lists->where('type', 'minus')->sum('price');

        $data = [
            'lists' => $lists,
            'users' => $users,
            'first' => $first,
            'last' => $last,
            'alladd' => $alladd,
            'allminus' => $allminus
        ];

        return view('admin.balance.analyze', $data);
    }

    public function balancePrinter($id)
    {
        $balance = Balance::with('user', 'exporter')->find($id);
        return view('admin.balance.print', ['item' => $balance]);
    }

    public function transactionReports(Request $request)
    {
        $fdate = strtotime($request->get('fsdate', null));
        $ldate = strtotime($request->get('lsdate', null));
        if ($request->get('fsdate', null) !== null && $request->get('lsdate', null) !== null)
            $lists = Transaction::where('created_at', '>', $fdate)->where('created_at', '<', $ldate)->with('user', 'buyer', 'content')->orderBy('id', 'DESC')->get();
        else
            $lists = Transaction::with('user', 'buyer', 'content')->orderBy('id', 'DESC')->get();
        $first = $lists->first();
        $last = $lists->last();
        $allPrice = $lists->sum('price');
        $userIncome = $lists->sum('income');
        $siteIncome = $allPrice - $userIncome;
        return view('admin.report.transaction', ['lists' => $lists, 'first' => $first, 'last' => $last, 'allPrice' => $allPrice, 'siteIncome' => $siteIncome]);
    }


    ###################
    ###Email Section###
    ###################

    public function emailCreate()
    {
        $userList = User::all();
        $template = EmailTemplate::all();
        return view('admin.email.new', array('users' => $userList, 'template' => $template));
    }

    public function emailTemplateLists()
    {
        $lists = EmailTemplate::all();
        return view('admin.email.templates', array('lists' => $lists));
    }

    public function emailTemplateCreate()
    {
        return view('admin.email.templateitem');
    }

    public function emailTemplateShow($id)
    {
        $item = EmailTemplate::find($id);
        return view('admin.email.templateitem', array('item' => $item));
    }

    public function emailTemplateDelete($id)
    {
        EmailTemplate::find($id)->delete();
        cache()->forget('email.template.' . $id);
        return back();
    }

    public function emailTemplateEdit(Request $request)
    {
        if ($request->id == '') {
            $template = new EmailTemplate;
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
            return redirect('/admin/email/template/item/' . $template->id);
        } else {
            $Template = EmailTemplate::find($request->id);
            $Template->update([
                'title' => $request->title,
                'template' => $request->template
            ]);
            cache()->forget('email.template.' . $Template->id);
            $Template->refresh();
        }
        return back();
    }

    public function emailSendMail(Request $request)
    {

        $send = sendMail($request->toArray());
        if ($send)
            $request->session()->flash('status', $send);
        else
            $request->session()->flash('status', 'error');
        return back();
    }


    ######################
    ###Discount Section###
    ######################
    public function discountLists()
    {
        $lists = Discount::orderBy('id', 'DESC')->get();
        return view('admin.discount.list', ['lists' => $lists]);
    }

    public function discountCreate()
    {
        return view('admin.discount.new');
    }

    public function discountStore(Request $request)
    {
        if (!empty($request->id)) {
            $gift = Discount::find($request->id);

        } else {
            $gift = new Discount;
            $gift->created_at = time();
        }

        $gift->expire_at = toTimestamp($request->expire_at);
        $gift->title = $request->title;
        $gift->code = $request->code;
        $gift->type = $request->type;
        $gift->off = $request->off;
        $gift->mode = $request->mode;
        $gift->save();
        return redirect('/admin/discount/edit/' . $gift->id);
    }

    public function discountEdit($id)
    {
        $item = Discount::find($id);
        cache()->forget('discount.' . $item->code);
        return view('admin.discount.edit', ['item' => $item]);
    }

    public function discountDelete($id)
    {
        $item = Discount::find($id);
        $item->delete();
        cache()->forget('discount.' . $item->code);
        return back();
    }

    public function discountContentCreate()
    {
        $contents = Content::all();
        $categoreis = ContentCategory::all();
        return view('admin.discount.newcontent', ['contents' => $contents, 'categoreis' => $categoreis]);
    }

    public function discountContentList()
    {
        $list = DiscountContent::with(['content.user', 'category'])->orderBy('id', 'DESC')->get();
        return view('admin.discount.contentlist', ['lists' => $list]);
    }

    public function discountContentStore(Request $request)
    {
        $fdate = strtotime($request->fdate) + 12600;
        $ldate = strtotime($request->ldate) + 12600;

        if ($request->type == 'content')
            $off_id = $request->off_id_content;
        elseif ($request->type == 'category')
            $off_id = $request->off_id_category;
        elseif ($request->type == 'all')
            $off_id = 0;
        else
            $off_id = 0;

        $array = [
            'type' => $request->type,
            'mode' => $request->mode,
            'off_id' => $off_id,
            'off' => $request->off,
            'first_date' => $fdate,
            'last_date' => $ldate,
            'created_at' => time()
        ];

        $discount = DiscountContent::create($array);

        return redirect('/admin/discount/content/edit/' . $discount->id);
    }

    public function discountContentEdit($id)
    {
        $contentDiscount = DiscountContent::find($id);
        $contents = Content::all();
        $categoreis = ContentCategory::all();
        return view('admin.discount.contentedit', ['contents' => $contents, 'categoreis' => $categoreis, 'discount' => $contentDiscount]);
    }

    public function discountContentDelete($id)
    {
        $DiscountContent = DiscountContent::find($id);
        if (!empty($DiscountContent)) {
            $DiscountContent->delete();
        }
        return back();
    }

    public function discountContentUpdate($id, Request $request)
    {
        $fdate = strtotime($request->fdate) + 12600;
        $ldate = strtotime($request->ldate) + 12600;

        if ($request->type == 'content')
            $off_id = $request->off_id_content;
        elseif ($request->type == 'category')
            $off_id = $request->off_id_category;
        else
            $off_id = '';

        $array = [
            'type' => $request->type,
            'mode' => $request->mode,
            'off_id' => $off_id,
            'off' => $request->off,
            'first_date' => $fdate,
            'last_date' => $ldate,
        ];

        DiscountContent::find($id)->update($array);

        return redirect('/admin/discount/content/edit/' . $id);
    }

    public function discountContentPublish($id)
    {
        DiscountContent::find($id)->update(['mode' => 'publish']);
        return back();
    }

    public function discountContentdraft($id)
    {
        DiscountContent::find($id)->update(['mode' => 'draft']);
        return back();
    }

    #################
    ## Ads Section ##
    #################
    public function adsPlans()
    {
        $lists = AdsPlan::orderBy('id', 'DESC')->get();
        return view('admin.ads.list', ['lists' => $lists]);
    }

    public function adsNewPlan()
    {
        return view('admin.ads.newplan');
    }

    public function adsNewPlanStore(Request $request)
    {
        $newAds = AdsPlan::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'day' => $request->get('day'),
            'mode' => $request->get('mode'),
        ]);
        return redirect('admin/ads/plan/edit/' . $newAds->id);
    }

    public function adsPlanEdit($id)
    {
        $plan = AdsPlan::find($id);
        return view('admin.ads.planedit', ['plan' => $plan]);
    }

    public function adsPlanEditStore($id, Request $request)
    {
        AdsPlan::find($id)->update($request->all());
        return back();
    }

    public function adsPlanDelete($id)
    {
        AdsPlan::find($id)->delete();
        return back();
    }

    public function adsBoxs()
    {
        $lists = AdsBox::orderBy('id', 'DESC')->get();
        return view('admin.ads.boxs', ['lists' => $lists]);
    }

    public function adsNewBox()
    {
        return view('admin.ads.newbox');
    }

    public function adsBoxStore(Request $request)
    {
        $new = AdsBox::create($request->all());
        return redirect('/admin/ads/box/edit/' . $new->id);
    }

    public function adsBoxEdit($id)
    {
        $item = AdsBox::find($id);
        return view('admin.ads.editbox', ['item' => $item]);
    }

    public function adsBoxDelete($id)
    {
        AdsBox::find($id)->delete();
        return back();
    }

    public function adsBoxUpdate($id, Request $request)
    {
        AdsBox::find($id)->update($request->all());
        return back();
    }

    public function adsRequests()
    {
        $lists = AdsRequest::with('plan', 'user', 'content')->orderBy('id', 'DESC')->get();
        return view('admin.ads.requests', ['lists' => $lists]);
    }

    public function adsVipList()
    {
        $contents = Content::where('mode', 'publish')->get();
        $lists = ContentVip::with('content')->orderBy('id', 'DESC')->get();
        return view('admin.ads.vip', ['contents' => $contents, 'lists' => $lists]);
    }

    public function adsVipStore(Request $request)
    {
        $fdate = strtotime($request->fdate) + 12600;
        $ldate = strtotime($request->ldate) + 12600;
        $cotnent = Content::find($request->content_id);
        ContentVip::create([
            'content_id' => $request->content_id,
            'category_id' => $cotnent->category_id,
            'first_date' => $fdate,
            'last_date' => $ldate,
            'mode' => $request->mode,
            'type' => $request->type,
            'description' => $request->description
        ]);
        cache()->forget('vip.content');
        cache()->forget('slider.content.vip.home');
        return back();
    }

    public function adsVipEdit($id)
    {
        $contents = Content::all();
        $vip = ContentVip::find($id);
        $lists = ContentVip::with('content')->orderBy('id', 'DESC')->get();
        return view('admin.ads.vipedit', ['contents' => $contents, 'lists' => $lists, 'vip' => $vip]);
    }

    public function adsVipUpdate(Request $request, $id)
    {
        $fdate = strtotime($request->fdate) + 12600;
        $ldate = strtotime($request->ldate) + 12600;
        $content = Content::find($request->content_id);
        ContentVip::find($id)->update([
            'content_id' => $request->content_id,
            'category_id' => $content->category_id,
            'first_date' => $fdate,
            'last_date' => $ldate,
            'mode' => $request->mode,
            'type' => $request->type,
            'description' => $request->description
        ]);
        cache()->forget('vip.content');
        cache()->forget('slider.content.vip.home');
        return back();
    }

    public function adsVipDelete($id)
    {
        ContentVip::find($id)->delete();
        cache()->forget('vip.content');
        cache()->forget('slider.content.vip.home');
        return back();
    }

    #####################
    ## Setting Section ##
    #####################
    public function settingStore(Request $request, $lunch = null)
    {
        if (isset($request->main_page_slider_content)) {
            $request->offsetSet('main_page_slider_content', implode(',', $request->main_page_slider_content));
        }

        if (isset($request->site_language)) {
            $request->request->add(['site_language' => strtolower($request->site_language)]);
        }

        $data = $request->all();

        foreach ($data as $index=>$d){
            if(is_array($d)){
                $data[$index] = json_encode($d);
            }
        }

        if (!empty($data) and is_array($data)) {
            foreach ($data as $option => $value) {
                cache()->forget('get.' . $option);
            }
        }

        Option::updateOrNew($data);
        if ($lunch != null)
            return Redirect::to(URL::previous() . '#' . $lunch);
        else
            return back();
    }

    public function settingBlog()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.blog', ['_setting' => $_setting]);
    }

    public function settingNotification()
    {
        $notification_template = NotificationTemplate::all();
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.notification', ['_setting' => $_setting, 'template' => $notification_template]);
    }

    public function settingMain()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.main', ['_setting' => $_setting]);
    }

    public function settingDisplay()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.display', ['_setting' => $_setting]);
    }

    public function settingContent()
    {
        $contents = Content::where('mode', 'publish')->get();
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.content', ['_setting' => $_setting, 'contents' => $contents]);
    }

    public function settingUser()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        $template = EmailTemplate::all();
        return view('admin.setting.user', ['_setting' => $_setting, 'template' => $template]);
    }

    public function settingTerm()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.term', ['_setting' => $_setting]);
    }

    public function settingSocial()
    {
        $list = Social::orderBy('sort')->get();
        return view('admin.setting.social', ['lists' => $list]);
    }

    public function settingSocialStore(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'icon' => 'required|string',
            'link' => 'required|string',
            'sort' => 'required|integer',
        ];

        $this->validate($request, $rules);

        if (isset($request->id)) {
            Social::find($request->id)->update($request->all());
            return back();
        } else {
            Social::create($request->all());
            return back();
        }
    }

    public function settingSocialEdit($id)
    {
        $item = Social::find($id);
        $list = Social::orderBy('sort')->get();
        return view('admin.setting.social', ['lists' => $list, 'item' => $item]);
    }

    public function settingSocialDelete($id)
    {
        Social::find($id)->delete();
        return back();
    }

    public function settingFooter()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.footer', ['_setting' => $_setting]);
    }

    public function settingPages()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.pages', ['_setting' => $_setting]);
    }

    public function settingDefaults()
    {
        $_setting = arrayToList(Option::all(), 'option', 'value');
        return view('admin.setting.default', ['_setting' => $_setting]);
    }

    public function settingViewTemplates()
    {
        $data = [
            'lists' => ViewTemplate::all(),
            'item' => []
        ];
        return view('admin.setting.view_templates', $data);
    }

    public function settingViewTemplatesStore(Request $request)
    {
        $this->validate($request, [
            'folder' => 'required|unique:view_templates,folder',
            'status' => 'required'
        ]);

        $data = $request->all();
        $status = false;
        if (!empty($data['status']) and $data['status'] == '1') {
            $status = true;
            ViewTemplate::where('status', true)
                ->update([
                    'status' => false
                ]);
        }

        ViewTemplate::create([
            'folder' => $data['folder'],
            'status' => $status,
            'created_at' => time()
        ]);

        cache()->forget('view.template');

        return redirect()->back();
    }

    public function settingViewTemplatesToggle(Request $request, $id)
    {
        $template = ViewTemplate::findOrFail($id);

        $template->update([
            'status' => !$template->status,
        ]);

        ViewTemplate::where('status', true)
            ->where('id', '!=', $template->id)
            ->update([
                'status' => false
            ]);

        cache()->forget('view.template');

        return redirect()->back();
    }

    public function settingViewTemplatesDelete(Request $request, $id)
    {
        $template = ViewTemplate::findOrFail($id);

        $template->delete();

        cache()->forget('view.template');

        return redirect()->back();
    }


    ######################
    ### Convert Section ##
    ######################
    public function videoConvert($id)
    {
        $part = ContentPart::find($id);
        if ($part) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/content-' . $part->content_id . '/video';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/log';
            if (!is_dir($storagePath)) {
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            if (!is_dir($logPath)) {
                File::makeDirectory($logPath, $mode = 0777, true, true);
            }
            $opening = getcwd() . parse_url(rawurldecode('/logo.mpg'), PHP_URL_PATH);
            $import = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/temp/temp.mpg';
            $export = $storagePath . '/part-' . $part->id . '.mp4';
            $log = $logPath . '/block.txt';
            $logo = getcwd() . get_option('video_watermark');
            //exec("ffmpeg -y -i $opening -i $import -c:v libx264 -b:v 1.5M -vf \"scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2\" -strict -2 -preset ultrafast $export 1> $log 2>&1");
            exec("cat $opening $import | ffmpeg -y -f mpeg -i - -qscale 0 -vcodec h264 $export 1> $log 2>&1");
            return Redirect::to(URL::previous() . '#convert');
            //dd(file_get_contents($log));
        } else {
            abort(404);
        }
    }

    public function videoCopy($id)
    {
        $part = ContentPart::find($id);
        if ($part) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/content-' . $part->content_id . '/video';
            if (!is_dir($storagePath)) {
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            $import = getcwd() . parse_url(rawurldecode($part->upload_video), PHP_URL_PATH);
            $info = pathinfo($import);
            $export = $storagePath . '/part-' . $part->id . '.' . $info['extension'];
            copy($import, $export);
            return Redirect::to(URL::previous() . '#convert');
        } else {
            abort(404);
        }
    }

    public function videoPreConvert($id)
    {
        $part = ContentPart::find($id);
        if ($part) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/temp';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/log';
            if (!is_dir($storagePath)) {
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }

            $log = $logPath . '/block.txt';
            $import = getcwd() . parse_url(rawurldecode($part->upload_video), PHP_URL_PATH);
            $export = $storagePath . '/temp.mpg';
            exec("ffmpeg -y -i $import -qscale 0 $export 1> $log 2>&1");
            return Redirect::to(URL::previous() . '#convert');
        }
    }

    public function videoConvertLogo($id)
    {
        $part = ContentPart::find($id);
        if ($part) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/content-' . $part->content_id . '/video';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/log';
            $logo = getcwd() . get_option('video_watermark');
            if (!is_dir($storagePath)) {
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            if (!is_dir($logPath)) {
                File::makeDirectory($logPath, $mode = 0777, true, true);
            }
            $import = getcwd() . parse_url(urlencode($part->upload_video), PHP_URL_PATH);
            $export = $storagePath . '/part-' . $part->id . '.mp4';
            $log = $logPath . '/block.txt';
            exec("ffmpeg -y -i $import -i $logo -filter_complex \"overlay = W - w - 10:H - h - 10\" -c:v libx264 -preset ultrafast $export");
            dd(file_get_contents($log));
            return Redirect::to(URL::previous() . '#convert');
        } else {
            abort(404);
        }
    }

    public function videoScreenShot(Request $request)
    {
        $patch = public_path() . $request->upload_video;
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source\content-' . $request->content_id . '\image';
        $export = $storagePath . '\thumbnail-' . $request->id . '.jpg';
        if (!file_exists($storagePath)) {
            File::makeDirectory($storagePath, $mode = 0777, true, true);
        }
        empty($request->intval) ? $intval = 20 : $intval = $request->intval;
        exec("ffmpeg -i $patch -deinterlace -an -ss $intval -f mjpeg -t 1 -r 1 -y -s $request->resolution $export 2>&1");
        return Redirect::to(URL::previous() . '#convert');
    }

    public function videoStreamAdmin($id)
    {
        $part = ContentPart::where('id', $id)
            ->where('mode', 'publish')
            ->with('content')
            ->first();

        if (!$part)
            abort(404);


        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = $storagePath . 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.mp4';

        if (!file_exists($file))
            abort(404);

        $stream = new VideoStream($file);
        $stream->start();

    }

    ######################
    ### Quizzes Section ##
    ######################

    public function QuizzesList()
    {
        if(!function_exists('checkQuiz')){
            return view('admin.purchase');
        }
        $quizzes = Quiz::with(['content', 'questions', 'QuizResults'])->paginate(15);

        if ($quizzes) {
            foreach ($quizzes as $quiz) {
                $QuizResults = $quiz->QuizResults;
                $passed_results = 0;
                $total_grade = 0;
                foreach ($QuizResults as $result) {
                    if ($result->status == 'pass') {
                        $passed_results += 1;
                    }
                    $total_grade += (int)$result->user_grade;
                }

                $quiz->passed = $passed_results;
                $quiz->average_grade = ($total_grade > 0) ? round($total_grade / count($QuizResults), 2) : 0;
            }

            $data = [
                'quizzes' => $quizzes,
            ];

            return view('admin.quizzes.list', $data);
        }
        abort(404);
    }

    public function QuizzesListExcel()
    {
        $quizzes = Quiz::all();
        return Excel::download(new QuizzesAdminExport($quizzes), trans('admin.quizzes') . '.xlsx');
    }

    public function QuizResults($quiz_id)
    {
        $quiz = Quiz::where('id', $quiz_id)
            ->with(['content', 'questions', 'QuizResults' => function ($query) {

            }])
            ->first();

        if ($quiz) {
            $quiz_results = QuizResult::where('quiz_id', $quiz->id)
                ->orderBy('status', 'desc')
                ->with('student')
                ->paginate(15);

            $data = [
                'quiz' => $quiz,
                'quiz_results' => $quiz_results,
            ];

            return view('admin.quizzes.result', $data);
        }

        abort(404);
    }

    public function QuizResultsExcel($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $quiz_results = QuizResult::where('quiz_id', $quiz->id)
            ->orderBy('status', 'desc')
            ->with('student', 'quiz')
            ->get();

        return Excel::download(new QuizzesResultAdminExport($quiz_results), trans('admin.quiz_results') . '.xlsx');
    }

    public function QuizResultsDelete($quiz_id, $result_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $result = QuizResult::where('id', $result_id)
            ->where('quiz_id', $quiz_id)
            ->first();
        if ($result) {
            $result->delete();
            return back()->with('msg', trans('admin.result_delete_msg'));
        }

        abort(404);
    }

    // ***********
    // CertificatesList
    // **********
    public function CertificatesList(Request $request)
    {
        if(!function_exists('checkQuiz')){
            return view('admin.purchase');
        }
        $query = Certificate::query();
        $filters = $request->all();

        if (!empty($filters['student_name'])) {
            $students = User::where('name', 'like', '%' . $filters['student_name'] . '%')->pluck('id')->toArray();
            if ($students and is_array($students)) {
                $query->whereIn('student_id', $students);
            }
        }

        if (!empty($filters['instructor'])) {
            $instructors = User::where('name', 'like', '%' . $filters['instructor'] . '%')->pluck('id')->toArray();
            if ($instructors and is_array($instructors)) {
                $quizzes = Quiz::whereIn('user_id', $instructors)->pluck('id')->toArray();
                if ($quizzes and is_array($quizzes)) {
                    $query->whereIn('quiz_id', $quizzes);
                }
            }
        }

        if (!empty($filters['quiz_name'])) {
            $quizzes = Quiz::where('name', 'like', '%' . $filters['quiz_name'] . '%')->pluck('id')->toArray();
            $query->whereIn('quiz_id', $quizzes);
        }

        $certificates = $query->paginate(15);

        $data = [
            'certificates' => $certificates
        ];
        return view('admin.certificates.list', $data);
    }

    public function CertificatesTemplatesList(Request $request)
    {
        if(!function_exists('checkQuiz')){
            return view('admin.purchase');
        }
        $templates = CertificateTemplate::all();

        $data = [
            'templates' => $templates,
        ];
        return view('admin.certificates.templates', $data);
    }

    public function CertificatesNewTemplate()
    {
        if(!function_exists('checkQuiz')){
            return view('admin.purchase');
        }
        return view('admin.certificates.create_template');
    }

    public function CertificatesTemplateStore(Request $request)
    {
        $rules = [
            'title' => 'required',
            'image' => 'required',
            'body' => 'required',
            'position_x' => 'required',
            'position_y' => 'required',
            'font_size' => 'required',
            'text_color' => 'required',
        ];
        $this->validate($request, $rules);

        $data = [
            'title' => $request->get('title'),
            'image' => $request->get('image'),
            'body' => $request->get('body'),
            'position_x' => $request->get('position_x'),
            'position_y' => $request->get('position_y'),
            'font_size' => $request->get('font_size'),
            'text_color' => $request->get('text_color'),
            'status' => $request->get('status'),
        ];

        if (!empty($request->get('id'))) {
            $data['updated_at'] = time();
            $template = CertificateTemplate::findOrFail($request->get('id'));
            $template->update($data);
        } else {
            $data['created_at'] = time();
            CertificateTemplate::create($data);
        }

        return redirect('/admin/certificates/templates');
    }

    public function CertificatesTemplatePreview(Request $request)
    {
        $data = [
            'image' => $request->get('image'),
            'body' => $request->get('body'),
            'position_x' => (int)$request->get('position_x', 120),
            'position_y' => (int)$request->get('position_y', 100),
            'font_size' => (int)$request->get('font_size', 26),
            'text_color' => $request->get('text_color', '#e1e1e1'),
        ];

        $body = str_replace('[user]', 'user name', $data['body']);
        $body = str_replace('[course]', 'course name', $body);
        $body = str_replace('[grade]', '55', $body);

        $img = Image::make(getcwd().$data['image']);
        $img->text($body, $data['position_x'], $data['position_y'], function ($font) use ($data) {
            $font->file(getcwd().'/assets/admin/fonts/nunito-v9-latin-regular.ttf');
            $font->size($data['font_size']);
            $font->color($data['text_color']);
        });
        return $img->response('png');
    }

    public function CertificatesTemplatesEdit($template_id)
    {
        $template = CertificateTemplate::findOrFail($template_id);

        $data = [
            'template' => $template
        ];
        return view('admin.certificates.create_template', $data);
    }

    public function CertificatesDownload($id)
    {
        $certificate = Certificate::findOrFail($id);
        return response()->download($certificate->file);
    }


    ############
    ## Live ####
    ############
    public function liveList(){
        $list = ClassModel::get();

        return view('admin.live.list',['list'=>$list]);
    }
    public function liveDetails($id){
        $live = MeetingDate::find($id);
        if(!$live)
            return abort(404);

        $Sells = Sell::where('content_id', $live->content_id)->pluck('user_id')->toArray();
        $list = User::whereIn('id',$Sells);

        return view('admin.live.details',['list'=>$list->paginate(20),'live'=>$live]);
    }
    public function liveDelete($id){
        MeetingDate::find($id)->delete();
        return back()->with('msg', trans('admin.result_delete_msg'));
    }
}
