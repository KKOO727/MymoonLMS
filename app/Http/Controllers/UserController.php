<?php

namespace App\Http\Controllers;

use App\Classes\CinetPay;
use App\Models\Article;
use App\Models\Balance;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Channel;
use App\Models\ChannelRequest;
use App\Models\ChannelVideo;
use App\Models\ClassModel;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentComment;
use App\Models\ContentMeta;
use App\Models\ContentPart;
use App\Models\ContentSupport;
use App\Models\CourseFeedbackModel;
use App\Models\DiscountContent;
use App\Models\Favorite;
use App\Models\Follower;
use App\Models\MeetingDate;
use App\Models\MeetingLink;
use App\Models\Notification;
use App\Models\NotificationStatus;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;
use App\Models\Record;
use App\Models\Requests;
use App\Models\Sell;
use App\Models\SellRate;
use App\Models\Tickets;
use App\Models\TicketsCategory;
use App\Models\TicketsMsg;
use App\Models\TicketsUser;
use App\Models\Transaction;
use App\Models\TransactionCharge;
use App\Models\Usermeta;
use App\Models\Country;
use App\User;
use DateInterval;
use DatePeriod;
use DateTime;
use \Jenssegers\Agent\Agent;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Razorpay\Api\Api;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Unicodeveloper\Paystack\Facades\Paystack;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function dashboard()
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user) {
            return redirect('/login');
        }
        $content_ids=[];
        $content_ids=[];
        $content_part_ids=[];
        $userMetas = get_all_user_meta($user->id);
        $events = $this->getClassSchedules($user);
        $completed = $this->getCompletedSchedules($user);
        $data = [
            'user' => $user,
            'meta' => $userMetas,
            'one' => $events['one'],
            'group' => $events['group'],
            'completed' => $completed,
        ];

        return view(getTemplate() . '.user.dashboard', $data);
    }

    public function passwordChange(Request $request)
    {
        $password = $request->get('password');
        $re_password = $request->get('repassword');

        if (!empty($password) and !empty($re_password) and $password == $re_password) {
            $user = auth()->user();
            $new_password = Hash::make($password);
            User::find($user->id)->update([
                'password' => $new_password
            ]);
            $request->session()->flash('msg', 'success');
            return back();
        }

        notify(trans('main.pass_confirmation_same'), 'danger');
        return back();
    }

    public function statusUpdate(Request $request)
    {
        $status = $request->get('mode');
        $user = Auth::user();

        if (!empty($status)) {
            User::find($user->id)->update([
                'mode' => $status
            ]);
            $request->session()->flash('msg', 'success');
            return back();
        }

        notify(trans('Account Status Updated Successfully'), 'danger');
        return back();
    }

    public function userProfile()
    {
        $user = auth()->user();
        $userMetas = Usermeta::where('user_id', $user->id)->pluck('value', 'option')->all();
        return view(getTemplate() . '.user.pages.profile', ['user' => $user, 'meta' => $userMetas]);
    }

    public function userProfileStore(Request $request)
    {
        $user = auth()->user();
        $user_table_data=array(
            'name'=>$request->name
        );
        User::find($user->id)->update($user_table_data);

        $data = $request->except('_token');
        $data = $request->except('name');

        Usermeta::updateOrNew($user->id, $data);
        $request->session()->flash('msg', 'Data successfully saved');
        return back();
    }

    public function userProfileMetaStore(Request $request)
    {
        $data = $request->except('_token');
        $user = auth()->user();

        if (is_array($data) and count($data) > 0) {
            Usermeta::updateOrNew($user->id, $data);

            foreach ($data as $key => $value) {
                cache()->forget('user.' . $user->id . '.meta.' . $key);
            }
            cache()->forget('user.' . $user->id);
            cache()->forget('user.' . $user->id . '.meta');
            cache()->forget('user.' . $user->id . '.metas.pluck.value');
        }
        $request->session()->flash('msg', 'Data successfully saved');
        return back();
    }

    public function userAvatarChange(Request $request)
    {
        $user = auth()->user();
        Usermeta::updateOrNew($user->id, $request->all());
        cache()->forget('user.' . $user->id . '.meta.avatar');
        cache()->forget('user.' . $user->id);
        return back();
    }

    public function userProfileImageChange(Request $request)
    {
        $user = auth()->user();
        Usermeta::updateOrNew($user->id, $request->all());
        return back();
    }

    ## Trip Mode ##
    public function tripModeDeActive()
    {
        $user = auth()->user();
        setUserMeta($user->id, 'trip_mode', '0');
        return back();
    }

    public function tripModeActive(Request $request)
    {
        $user = auth()->user();
        setUserMeta($user->id, 'trip_mode', '1');
        setUserMeta($user->id, 'trip_mode_date', strtotime($request->trip_mode_date) + 12600);
        setUserMeta($user->id, 'trip_mode_date_t', $request->trip_mode_date_t);
        return back();
    }

    #############
    #### Video ####
    #############

    public function userBuyLists()
    {

        $user = auth()->user();
        $buyListQuery = Sell::where('buyer_id', $user->id)->orderBy('id', 'DESC');

        if ($user->type == 'Teacher') {
            $buyList = $buyListQuery->with(['content' => function ($q) {
                $q->with(['metas', 'category', 'user']);
            }, 'transaction.balance', 'rate' => function ($r) use ($user) {
                $r->where('user_id', $user->id)->first();
            }])->get();

        } else {
            $buyList = $buyListQuery->with(['content' => function ($q) {
                $q->with(['metas', 'category', 'user']);
            }, 'transaction.balance', 'rate' => function ($r) use ($user) {
                $r->where('user_id', $user->id)->first();
            }])->where('type', '<>', 'subscribe')
                ->get();

        }

        return view(getTemplate() . '.user.sell.buy', ['list' => $buyList]);
    }

    public function userBuyPrint($id)
    {
        $user = auth()->user();
        $buyQuery = Sell::where('id', $id)->where('buyer_id', $user->id);

        $buy = $buyQuery->with(['content' => function ($q) {
            $q->with(['metas', 'category', 'user']);
        }, 'transaction.balance'])
            ->first();

        return view(getTemplate() . '.user.sell.print', ['title' => trans('main.print_invoice'), 'item' => $buy]);
    }

    public function userBuyConfirm(Request $request, $id)
    {
        $user = auth()->user();
        $sell = Sell::where('id', $id)->where('buyer_id', $user->id)->first();
        if (!$sell) {
            return abort(404);
        }

        if ($sell->post_confrim != '') {
            return redirect()->back()->with('msg', trans('main.parcel_confirm'));
        }

        $sell->update([
            'post_confirm' => $request->post_confirm,
            'post_feedback' => $request->post_feedback
        ]);

        return redirect()->back()->with('msg', trans('main.parcel'));
    }

    public function userBuyRateStore($id, $rate)
    {
        $user = auth()->user();
        $ifHasSell = Sell::where('buyer_id', $user->id)->find($id);
        if ($ifHasSell) {
            $sellRate = SellRate::firstOrNew(['user_id' => $user->id, 'sell_id' => $id]);
            $sellRate->rate = $rate;
            $sellRate->seller_id = $ifHasSell->user_id;
            $sellRate->save();
            return 1;
        }
        return 0;
    }

    ## Subscribe ##
    public function subscribeList(Request $request)
    {
        $user = auth()->user();
        $buyList = Sell::with(['content' => function ($q) {
            $q->with(['metas', 'category', 'user']);
        }, 'transaction.balance', 'rate' => function ($r) use ($user) {
            $r->where('user_id', $user->id)->first();
        }])->where('buyer_id', $user->id)->where('type', 'subscribe')->orderBy('id', 'DESC')->get();
        return view(getTemplate() . '.user.sell.subscribe', ['list' => $buyList]);
    }


    ## Off Section ##
    public function userDiscounts()
    {
        $user = auth()->user();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();
        $userContentIds = $userContent->pluck('id')->toArray();
        $discounts = DiscountContent::with('content')->whereIn('off_id', $userContentIds)->where('type', 'content')->get();
        return view(getTemplate() . '.user.sell.off', ['userContent' => $userContent, 'discounts' => $discounts]);
    }

    public function userDiscountEdit($id)
    {
        $user = auth()->user();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();
        $userContentIds = $userContent->pluck('id')->toArray();
        $discounts = DiscountContent::with('content')->whereIn('off_id', $userContentIds)->where('type', 'content')->get();
        $discount = DiscountContent::with('content.user')->find($id);
        if ($discount->content->user->id == $user->id) {
            return view(getTemplate() . '.user.sell.off', ['userContent' => $userContent, 'discounts' => $discounts, 'discount' => $discount]);
        } else {
            return abort(404);
        }
    }

    public function userDiscountStore(Request $request)
    {
        $user = auth()->user();
        $check_user_has_content = Content::where('user_id', $user->id)->where('id', $request->off_id)->count();

        if ($check_user_has_content == 1) {
            $fist_date = strtotime($request->first_date) + 12600;
            $last_date = strtotime($request->last_date) + 12600;
            $array = [
                'first_date' => $fist_date,
                'last_date' => $last_date,
                'off_id' => $request->off_id,
                'off' => $request->off,
                'mode' => 'draft',
                'type' => 'content',
                'created_at' => time()
            ];
            DiscountContent::create($array);
            return redirect()->back()->with('msg', trans('main.discount_add_success'));
        }
    }

    public function userDiscountEditStore($id, Request $request)
    {
        $user = auth()->user();
        $check_user_has_content = Content::where('user_id', $user->id)->where('id', $request->off_id)->count();

        if ($check_user_has_content == 1) {
            $fist_date = strtotime($request->first_date) + 12600;
            $last_date = strtotime($request->last_date) + 12600;
            $array = [
                'first_date' => $fist_date,
                'last_date' => $last_date,
                'off_id' => $request->off_id,
                'off' => $request->off,
                'mode' => 'draft',
                'type' => 'content',
                'created_at' => time()
            ];
            DiscountContent::find($id)->update($array);
            return redirect()->back()->with('msg', trans('main.discount_edit'));
        }
    }

    public function userDiscountDelete($id)
    {
        $user = auth()->user();
        $discount = DiscountContent::with('content.user')->find($id);
        if ($discount->content->user->id == $user->id) {
            DiscountContent::find($id)->delete();
            return redirect()->back()->with('msg', trans('main.discount_delete'));
        } else {
            return redirect()->back()->with('msg', trans('main.discount_delete_unable'));
        }
    }







    ## records Section ##

    public function records()
    {
        $user = auth()->user();
        $lists = Record::where('user_id', $user->id)->with('category')->withCount('fans')->orderBy('id', 'DESC')->get();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();
        $contentMenu = ContentCategory::with(['childs', 'filters' => function ($q) {
            $q->with(['tags']);
        }])->get();
        return view(getTemplate() . '.user.record.record', ['lists' => $lists, 'menus' => $contentMenu, 'userContent' => $userContent]);
    }

    public function recordEdit($id)
    {
        $user = auth()->user();
        $lists = Record::where('user_id', $user->id)->with('category')->withCount('fans')->orderBy('id', 'DESC')->get();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();
        $record = Record::where('user_id', $user->id)->find($id);
        $contentMenu = ContentCategory::with(['childs', 'filters' => function ($q) {
            $q->with(['tags']);
        }])->get();
        if (!$record)
            abort(404);
        return view(getTemplate() . '.user.record.record', ['lists' => $lists, 'menus' => $contentMenu, 'userContent' => $userContent, 'record' => $record]);
    }

    public function recordDelete($id)
    {
        $user = auth()->user();
        $record = Record::where('user_id', $user->id)->find($id);
        $record->update(['mode' => 'delete']);
        return redirect()->back()->with('msg', trans('main.unpublish_request_sent'));
    }

    public function recordStore(Request $request)
    {
        $user = auth()->user();
        Record::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'content_id' => $request->content_id,
            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description,
            'mode' => 'draft',
            'created_at' => time()
        ]);
        return redirect()->back()->with('msg', trans('main.content_approval'));
    }

    public function recordUpdate($id, Request $request)
    {
        $user = auth()->user();
        $record = Record::where('user_id', $user->id)->find($id);
        $record->update([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'content_id' => $request->content_id,
            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description,
            'mode' => 'draft',
        ]);
        return redirect()->back()->with('msg', trans('main.content_edit'));
    }

    ## request Section ##
    public function requests()
    {
        $user = auth()->user();
        $lists = Requests::where('user_id', $user->id)->orWhere('requester_id', $user->id)->with(['category', 'requester', 'suggestions' => function ($q) {
            $q->with(['content', 'user']);
        }])->withCount(['fans', 'suggestions'])->orderBy('id', 'DESC')->get();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();


        $data = [
            'lists' => $lists,
            'menus' =>[],
            'userContent' => $userContent
        ];

        return view(getTemplate() . '.user.request.request', $data);
    }

    public function requestStore(Request $request)
    {
        $user = auth()->user();
        Requests::create([
            'user_id' => 0,
            'requester_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'mode' => 'draft',
            'created_at' => time()
        ]);
        return redirect()->back()->with('msg', trans('main.request_sent'));
    }

    public function requestEdit($id)
    {
        $user = auth()->user();
        $lists = Requests::where('user_id', $user->id)->orWhere('requester_id', $user->id)->with(['category', 'requester', 'suggestions' => function ($q) {
            $q->with(['content', 'user']);
        }])->withCount(['fans', 'suggestions'])->orderBy('id', 'DESC')->get();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->get();
        $request = Requests::where('requester_id', $user->id)->find($id);
        if (!$request)
            abort(404);
        return view(getTemplate() . '.user.request.request', ['lists' => $lists, 'menus' => contentMenu(), 'userContent' => $userContent, 'request' => $request]);
    }

    public function requestUpdate($id, Request $request)
    {
        $user = auth()->user();
        $req = Requests::where('requester_id', $user->id)->find($id);
        if (!$req)
            return abort(404);
        $req->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'mode' => 'draft',
        ]);
        return redirect()->back()->with('msg', trans('main.request_edit'));
    }

    public function requestAdmit(Request $request)
    {
        $user = auth()->user();
        $id = $request->get('request_id', null);

        if (!empty($id)) {
            $uRequest = Requests::where('id', $id)->where('requester_id', $user->id)->first();;
            if ($uRequest) {
                $uRequest->update(['content_id' => $request->content_id]);
            }
        }

        return redirect()->back();
    }

    public function requestDelete($id)
    {
        $user = auth()->user();
        $req = Requests::where('id', $id)->where('requester_id', $user->id)->first();
        if (!empty($req)) {
            $req->delete();
        }
        return redirect()->back();
    }


    ## Articles Section ##
    public function articles()
    {
        $user = auth()->user();
        $lists = Article::with(['category'])->where('user_id', $user['id'])->orderBy('id', 'DESC')->get();
        return view(getTemplate() . '.user.article.list', ['lists' => $lists]);
    }

    public function articleNew()
    {
        return view(getTemplate() . '.user.article.new');
    }

    public function articleStore(Request $request)
    {
        $user = auth()->user();
        $request->request->add(['user_id' => $user['id'], 'created_at' => time()]);
        $article = Article::create($request->toArray());
        return redirect('/user/article/edit/' . $article->id)->with('msg', trans('main.article_success'));
    }

    public function articleEdit($id)
    {
        $user = auth()->user();
        $article = Article::where('user_id', $user['id'])->find($id);
        if (!$article)
            return abort(404);
        return view(getTemplate() . '.user.article.edit', ['article' => $article]);
    }

    public function articleUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $article = Article::where('user_id', $user['id'])->find($id);
        if (!$article)
            return abort(404);
        $article->update($request->toArray());
        return redirect('/user/article/edit/' . $id);
    }

    public function articleDelete($id)
    {
        $user = auth()->user();
        $article = Article::where('user_id', $user['id'])->find($id);
        $article->update(['mode' => 'delete']);
        return back();
    }


    #################
    #### Channel ####
    #################

    public function channelList()
    {
        $user = auth()->user();
        $channels = Channel::withCount('contents')->where('user_id', $user->id)->get();
        return view(getTemplate() . '.user.channel.list', ['channels' => $channels]);
    }

    public function channelNew()
    {
        return view(getTemplate() . '.user.channel.new');
    }

    public function channelStore(Request $request)
    {
        $user = auth()->user();
        $ifChannelExist = Channel::where('username', $request->username)->first();
        if (!empty($ifChannelExist)) {
            $request->request->add(['mode' => 'pending']);
            $request->session()->flash('Message', 'duplicate_username');
            return back();
        } else {
            $request->request->add(['user_id' => $user->id, 'mode' => get_option('user_channel_mode')]);
            Channel::create($request->all());
            $request->session()->flash('Message', 'successfull');
            return back();
        }
    }

    public function channelDelete($id)
    {
        $user = auth()->user();
        Channel::where('id', $id)->where('user_id', $user->id)->delete();
        return redirect('/user/channel');
    }

    public function channelEdit($id)
    {
        $user = auth()->user();
        $item = Channel::where('id', $id)->where('user_id', $user->id)->first();
        $channels = Channel::where('user_id', $user->id)->get();
        if ($item)
            return view(getTemplate() . '.user.channel.edit', ['edit' => $item, 'channels' => $channels]);
        else
            return back();
    }

    public function channelUpdate($id, Request $request)
    {
        $user = auth()->user();
        $request->request->add(['mode' => 'pending']);
        $data = $request->except(['_token']);
        Channel::find($id)->where('user_id', $user->id)->update($data);
        $request->session()->flash('Message', 'successfull');
        return back();
    }

    public function channelRequest($id)
    {
        $user = auth()->user();
        $channelsRequest = ChannelRequest::with('channel')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
        $channels = Channel::withCount('contents')->where('user_id', $user->id)->get();
        return view(getTemplate() . '.user.channel.request', ['id' => $id, 'requests' => $channelsRequest, 'channels' => $channels]);
    }

    public function channelRequestStore(Request $request)
    {
        $user = auth()->user();
        $check = Channel::where('user_id', $user->id)->find($request->channel_id);
        if (!$check)
            return back();

        ChannelRequest::create([
            'title' => $request->title,
            'channel_id' => $request->channel_id,
            'user_id' => $user->id,
            'mode' => 'draft',
            'attach' => $request->attach,

        ]);
        return redirect()->back()->with('msg', trans('main.channel_success'));
    }

    public function chanelVideo($id)
    {
        $user = auth()->user();
        $chanel = Channel::with('contents.content')->where('user_id', $user->id)->find($id);
        if (!$chanel)
            return abort(404);

        $userContents = Content::where('mode', 'publish')->where('user_id', $user->id)->get();
        return view(getTemplate() . '.user.channel.video', ['chanel' => $chanel, 'userContents' => $userContents]);
    }

    public function chanelVideoStore(Request $request, $id)
    {
        $user = auth()->user();
        $chanel = Channel::where('user_id', $user->id)->find($id);
        if (!$chanel)
            return abort(404);

        ChannelVideo::create([
            'content_id' => $request->content_id,
            'user_id' => $user->id,
            'chanel_id' => $chanel->id,
        ]);

        return redirect()->back()->with('msg', trans('main.add_success'));
    }

    public function chanelVideoDelete($id)
    {
        $user = auth()->user();
        ChannelVideo::where('user_id', $user->id)->find($id)->delete();
        return redirect()->back();
    }

    #################
    #### Content ####
    #################

    public function contentList()
    {
        $user = auth()->user();
        $lists = Content::where('user_id', $user->id)->where('type','!=','1')->with('category')->withCount('sells', 'partsactive')->orderBy('id', 'DESC')->get();
        return view(getTemplate() . '.user.content.list', ['lists' => $lists]);
    }

    public function contentDelete($id)
    {
        $user = auth()->user();
        Content::where('id', $id)->where('user_id', $user->id)->update(['mode' => 'delete']);
        contentCacheForget();
        return back();
    }

    public function contentRequest($id)
    {
        $user = auth()->user();
        $content = Content::where('user_id', $user->id)->find($id);

        ## Notification Center
        sendNotification(0, ['[u.name]' => $user->name, '[c.title]' => $content->title], get_option('notification_template_content_pre_publish'), 'user', $user->id);

        $content->update(['mode' => 'request']);
        contentCacheForget();
        return back();
    }

    public function contentDraft($id)
    {
        $user = auth()->user();
        Content::where('id', $id)->where('user_id', $user->id)->update(['mode' => 'draft']);
        contentCacheForget();
        return back();
    }

    public function contentNew()
    {
        $contentMenu = ContentCategory::with(['childs', 'filters' => function ($q) {
            $q->with(['tags']);
        }])->get();
        return view(getTemplate() . '.user.content.new', ['menus' => $contentMenu]);
    }

    public function contentStore(Request $request)
    {
        $user = auth()->user();
        $newContent = $request->except(['_token']);
        $newContent['mode'] = 'draft';
        $newContent['user_id'] = $user->id;
        $content_id = Content::insertGetId($newContent);
        return redirect('/user/content/edit/' . $content_id);

    }

    public function contentEdit($id)
    {
        $user = auth()->user();

        if($id == 'onetoone'){
            $item = Content::where('type', '1')->where('user_id', $user->id)->first();
            if(is_null($item)){
                $create=Content::create([
                    'title'=>'One to One Configuration',
                    'type'=>'1',
                    'user_id'=>$user->id,
                    'mode'=>'publish',
                ]);
            }
            $item = Content::where('type', '1')->where('user_id', $user->id)->first();
        }else{
            $item = Content::with('parts', 'filters')->where('id', $id)->where('user_id', $user->id)->first();
            //$meta = arrayToList($item->metas, 'option', 'value');
            $meta=[];

            $preCourseContent = [];
        }


        $contentMenu = ContentCategory::with(['childs', 'filters' => function ($q) {
            $q->with(['tags']);
        }])->get();

        if($id == 'onetoone'){
            return view(getTemplate() . '.user.content.one-to-one', ['item' => $item, 'menus' => $contentMenu]);
        }else{
            return view(getTemplate() . '.user.content.edit', ['item' => $item, 'meta' => $meta, 'menus' => $contentMenu, 'preCourse' => $preCourseContent]);
        }


    }

    public function contentUpdate(Request $request)
    {
        $id=$request->id;
        $user = auth()->user();
        $request->request->add(['mode' => 'pending']);
        $content = Content::where('user_id', $user->id)->find($id);
        $data=array();
        if ($content) {
            $data=array(
                'title'=>$request->title,
                'content'=>$request->content,
                'level'=>$request->level,
                'max_student'=>$request->max_student,
                'seats_available'=>$request->max_student,
                'price'=>convertToUSD($request->price),
                'age_from'=>$request->age_from,
                'age_to'=>$request->age_to,
                'date_from'=>$request->date_from,
                'date_to'=>$request->date_to,
                'image'=>$request->image,
                'mode'=>'draft',
            );
            if(isset($_POST['language']) && count($_POST['language']) > 0){
                $data['language']=implode(',',$_POST['language']);
            }

            if(isset($_POST['category_id']) && count($_POST['category_id']) > 0){
                $data['category_id']=implode(',',$_POST['category_id']);
            }

            $content->update($data);
            //echo 'true';
        } else {
            //echo 'false';
        }

        return redirect('/user/content/edit/' . $id);

    }

    public function contentUpdateRequest($id, Request $request)
    {
        $user = auth()->user();
        $request->request->add(['mode' => 'request']);
        $content = Content::where('user_id', $user->id)->find($id);

        if ($content) {
            $request = $request->all();
            print_r($request);
            if (isset($request['filters']) && count($request['filters']) > 0) {
                $content->filters()->sync($request['filters']);
            }
            unset($request['filters']);
            $content->update($request);
            contentCacheForget();
            echo 'true';
        } else {
            echo 'false';
        }

    }

    public function contentMetaStore($id, Request $request)
    {
        $user = auth()->user();
        $content = Content::where('user_id', $user->id)->find($id);
        if ($content) {
            ContentMeta::updateOrNew($content->id, $request->all());
            echo 'true';
        }
        $date_from=$_POST['date_from'];
        $date_to=$_POST['date_to'];

        dd($_POST['date_from']);

    }

    //One to One configuration
    public function contentSaveOneForm(Request $request)
    {
        $user = auth()->user();
        $data=array(
            'title'=>'One to One',
            'content'=>$request->content,
            'price'=>convertToUSD($request->price),
            'trial_price'=>convertToUSD($request->trial_price),
            'age_from'=>$request->age_from,
            'age_to'=>$request->age_to,
            'date_from'=>$request->date_from,
            'date_to'=>$request->date_to,
            'mode'=>'publish',
        );

        if(isset($_POST['language']) && count($_POST['language']) > 0){
            $data['language']=implode(',',$_POST['language']);
        }

        if(isset($_POST['category_id']) && count($_POST['category_id']) > 0){
            $data['category_id']=implode(',',$_POST['category_id']);
        }


        $check=Content::where('type', '1')->where('user_id',$user->id)->get()->count();
        if($check > 0){
            $update=Content::where('type', '1')->where('user_id',$user->id)->update($data);
        }else{
            $update=Content::where('type', '1')->where('user_id',$user->id)->insert($data);
        }

        $meta_update=Usermeta::updateOrNew($user->id,['meta_hourly_rate'=>$data['price']]);
        $meta_update=Usermeta::updateOrNew($user->id,['meta_hourly_trial_rate'=>$data['trial_price']]);
        return $update;
    }

    public function contentSaveOnePlan(Request $request)
    {

        $user = auth()->user();
        $data=array();
        $duration= $request->duration;
        $content = Content::where('user_id', $user->id)->find($request->content_id);
        if ($content) {

            $data=array(
                'title'=> $request->plan_title,
                'price'=> convertToUSD($request->price),
                'content_id'=> $request->content_id,
                'class_number'=> $request->class_number,
                'date_from'=> $request->date_from,
                'date_to'=> $request->date_to,
                'duration'=> $request->duration,
                'mode'=> 'publish'
            );

            if(isset($_POST['part-edit-id'])){
                $part_id=$request->input('part-edit-id');
                $newPart = ContentPart::where('id',$part_id)->update($data);
            }else{
                $part_id = ContentPart::insertGetId($data);
            }



            $schedule=array();
            $from=date('Y-m-d',strtotime($request->date_from));
            $to=date('Y-m-d',strtotime($request->date_to));
            $begin = new DateTime($from);
            $end = new DateTime($to);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            $del = Schedule::where('part_id',$part_id)->delete();

            for($i=1;$i<=7;$i++){
                if(isset($_POST['day'][$i])){
                    $actual_day=substr($_POST['day'][$i],0,3);
                    $start_time = $_POST['start_time'][$i];
                    $end_time = $start_time + ($duration*60);

                    foreach ($period as $dt) {
                        $current_day=$dt->format("D");

                        if($actual_day == $current_day){
                            $start=$dt->format("Y-m-d").' '.date("H:i:s", $start_time);
                            $ends=$dt->format("Y-m-d").' '.date("H:i:s", $end_time);

                            $insertArr = [ 'title' => $request->plan_title,
                                'start' => strtotime($start)*1000,
                                'end' => strtotime($ends)*1000,
                                'course_id' => $request->content_id,
                                'course_type' => '1',
                                'part_id' => $part_id,
                                'user_id' => $user->id,
                            ];
                            $event[] = Schedule::insertGetId($insertArr);
                        }
                    }

                    /*
                    $schedule[$i]=array(
                        'day'=>$_POST['day'][$i],
                        'start_time'=>$_POST['start_time'][$i],
                        'end_time'=>$end,
                    );
                    */
                }
            }


            return 'success';
        } else {
            return 'error';
        }

    }

    public function contentUpdateOnePlan(Request $request, $id)
    {
        $user = auth()->user();
        $content = Content::where('user_id', $user->id)->find($request->content_id);
        if ($content) {
            $request->request->add(['mode' => 'request']);
            ContentPart::find($id)->update($request->all());
            return back();
        } else {
            return back();
        }
    }

    public function generateDaysNew(Request $request){

        $start = strtotime('00:00');
        $end = strtotime('24:00');
        $str='';
        $schedule=array();
        $done=array();

        $events=Schedule::where('course_id',$request->course_id)->where('part_id', null)->get();

        foreach($events as $event){
            $day_format=date('Y-m-d H:i:s',$event->start/1000);
            $actual_day=date('D',strtotime($day_format));
            if(!in_array($actual_day,$done)){
                $start_hour=date('H:i',$event->start/1000);
                $end_hour=date('H:i',$event->end/1000);
                $schedule[$actual_day]=array('start'=>$start_hour,'end'=>$end_hour);
                $done[]=$actual_day;
            }

        }

        //dd($actual_day.' start  ' .$start_hour.' end '.$end_hour);
        //dd($schedule);


        for($j=1;$j<=$request->num;$j++){
            $str .='<div class="form-group">

                    <label class="control-label col-md-2 tab-con">Day '.$j.'</label>
                     <div class="col-md-5 tab-con"><select id="day'.$j.'" name="day['.$j.']" class="form-control" onchange="getHours('.$j.')">';
            $str .='<option value="">Please select</option>';
            foreach($schedule as $day=>$time) {
                $str .='<option value="' . $day . '">' . $day . '</option>';
            }
            $str .='</select></div>';
            $str .='<label class="control-label col-md-1 tab-con">Start time</label>
                     <div class="col-md-4 tab-con"><select id="start_time'.$j.'" name="start_time['.$j.']" class="form-control">';
            for ($i=$start; $i<=$end; $i = $i + 30*60) {
                //$str .='<option value="' . $i . '">' . date("g:i A", $i) . '</option>';
            }
            $str .='</select></div>';
            /*
            $str .='<label class="control-label col-md-1 tab-con">End time</label>
                     <div class="col-md-2 tab-con"><select name="end_time['.$j.']" class="form-control">';
            for ($i=$start; $i<=$end; $i = $i + 30*60) {
                $str .='<option value="' . $i . '">' . date("g:i A", $i) . '</option>';
            }
            $str .='</select></div></div>';
            */
            $str .='</div>';
        }
        return $str;
    }

    public function getHours(Request $request){

        $str='';
        $done=array();

        $events=Schedule::where('course_id',$request->course_id)->where('part_id', null)->get();

        foreach($events as $event){
            $day_format=date('Y-m-d H:i:s',$event->start/1000);
            $actual_day=date('D',strtotime($day_format));
            if($actual_day == $request->day){
                if(!in_array($event->start,$done)){
                    $start_hour=strtotime(date('H:i',$event->start/1000));
                    $end_hour=strtotime(date('H:i',$event->end/1000));
                    for ($i=$start_hour; $i<=$end_hour; $i = $i + 30*60) {
                        $str .='<option value="' . $i . '">' . date("g:i A", $i) . '</option>';
                    }
                    $done[]=$event->start;
                }
            }

        }
        return $str;
    }

    public function generateDaysEdit(Request $request){

        $str='';
        $done=array();

        $events=Schedule::where('course_id',$request->course_id)->where('part_id', null)->get();
        foreach($events as $event){
            $day_format=date('Y-m-d H:i:s',$event->start/1000);
            $actual_day=date('D',strtotime($day_format));
            if(!in_array($actual_day,$done)){
                $done[]=$actual_day;
            }
        }

        $events=Schedule::where('part_id',$request->id)->get();
        $done_days=array();


        $num =$request->num;
        $schedule=array();
        if($num > 0){
            $k=1;
            foreach($events as $event){

                $day_format=date('Y-m-d H:i:s',$event->start/1000);
                $actual_day=date('D',strtotime($day_format));
                $day_format=date('H:i:s',$event->start/1000);
                $start_time=strtotime($day_format);
                if(!in_array($actual_day,$done_days)){
                    $schedule[$k]=array('day'=>$actual_day,'start_time'=>$start_time);
                    $k++;
                    $done_days[]=$actual_day;
                }
            }
        }

        for($j=1;$j<=$num;$j++){
            $str .='<div class="form-group">
                    <label class="control-label col-md-2 tab-con">Day '.$j.'</label>
                     <div class="col-md-5 tab-con"><select id="edit_day'.$j.'" name="day['.$j.']" class="form-control" onchange="getEditHours('.$j.')">';
            foreach($done as $day) {
                $str .='<option value="' . $day . '"';
                if(isset($schedule[$j])){
                    if($schedule[$j]['day']==$day){
                        $str .=' selected ';
                    }
                }
                $str .='>' . $day . '</option>';
            }
            $str .='</select></div>';
            $str .='<label class="control-label col-md-1 tab-con">Start time</label>
                     <div class="col-md-4 tab-con"><select id="edit_start_time'.$j.'" name="start_time['.$j.']" class="form-control">';
            $str .='<option value="' . $schedule[$j]['start_time'] . '" selected>' . date("g:i A", $schedule[$j]['start_time']) . '</option>';

            $str .='</select></div>';

            /*
            $str .='<label class="control-label col-md-1 tab-con">End time</label>
                     <div class="col-md-2 tab-con"><select name="end_time['.$j.']" class="form-control">';
            for ($i=$start; $i<=$end; $i = $i + 30*60) {
                $str .='<option value="' . $i . '"';
                if(isset($schedule[$j])){
                    if($schedule[$j]['end_time']==$i){
                        $str .=' selected ';
                    }
                }
                $str .='>' . date("g:i A", $i) . '</option>';
            }
            $str .='</select></div></div>';
            */
            $str .='</div>';
        }
        return $str;
    }



    ## Part Section ##

    public function contentPartList($id)
    {
        $user = auth()->user();

        $content = Content::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['parts' => function ($q) {
                $q->orderBy('sort');
            }])->first();

        if (!empty($content)) {
            $data = [
                'lists' => $content->parts,
                'id' => $id
            ];

            return view(getTemplate() . '.user.content.part.list', $data);
        }

        abort(404);
    }

    public function contentPartNew($id)
    {
        return view(getTemplate() . '.user.content.part.new', ['id' => $id]);
    }

    public function contentPartEdit($id)
    {
        $user = auth()->user();
        $contentPart = ContentPart::with('content')->find($id);
        if ($contentPart && $contentPart->content->user_id = $user->id) {
            if($contentPart->price == null || $contentPart->price ==''){
                $contentPart->price=0;
            }
            $contentPart->price=ceil(currency(floatval($contentPart->price),null,null,false));
            return $contentPart;
            //return view(getTemplate() . '.user.content.part.edit', ['part' => $contentPart]);
        } else {
            return 0;
        }
    }

    public function contentPartDelete($id)
    {
        $user = auth()->user();
        $part = ContentPart::with('content')->find($id);
        if ($part->content->user_id == $user->id) {

            if(!empty($part->upload_video) && $part->server == 'vimeo'){
                $delete=Vimeo::request($part->upload_video, [], 'DELETE');
            }

            $part = ContentPart::where('id',$id)->delete();
        }
        return true;
    }

    public function contentPartDraft($id)
    {
        $user = auth()->user();
        $part = ContentPart::with('content')->find($id);
        if ($part->content->user_id == $user->id) {
            $part->update(['mode' => 'publish']);
        }
        return back();
    }

    public function contentPartRequest($id)
    {
        $user = auth()->user();
        $part = ContentPart::with('content')->find($id);
        if ($part->content->user_id == $user->id) {
            $part->update(['mode' => 'publish']);
        }
        return back();
    }

    public function contentPartStore(Request $request)
    {
        $user = auth()->user();
        $content = Content::where('user_id', $user->id)->find($request->content_id);
        if ($content) {
            $data=[];
            $video=$request->upload_video;
            if(!empty($video)){
                $video=substr($video, 1);
                $vimeo= Vimeo::upload($video);
                $rename=Vimeo::request($vimeo, ['name' => $request->title], 'PATCH');
                //$vimeo=Vimeo::request($vimeo, [],'GET');

                //$embed=$vimeo['body']['embed']['html'];
                //$embed_exploded=explode(' ',$embed);
                // $only_url=str_replace(['src="','"'],['',''],$embed_exploded[1]);
                //$data['upload_video']=$only_url;
                $data['upload_video']=$vimeo;
            }
            $data['mode'] = 'publish';
            $data['content_id'] = $request->content_id;
            $data['server'] = $request->input('server');
            $data['sort'] = $request->sort;
            $data['description'] = $request->description;
            $data['size'] = $request->size;
            $data['duration'] = $request->duration;
            $data['title'] = $request->title;

            $newPart = ContentPart::create($data);
            return redirect('/user/content/part/list/' . $content->id);
        } else {
            echo 'error';
        }

    }

    public function contentPartUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $content = Content::where('user_id', $user->id)->find($request->content_id);
        if ($content) {
            $request->request->add(['mode' => 'publish']);

            ContentPart::find($id)->update($request->all());
            $content = Content::where('id', $request->content_id)->update(['mode'=>'draft']);
            return back();
        } else {
            return back();
        }
    }

    ## Json Section
    public function contentPartsJson($id)
    {
        $user = auth()->user();
        $result = [];
        $content = Content::with(['parts' => function ($q) {
            $q->orderBy('sort');
        }])->where('user_id', $user->id)->find($id);
        foreach ($content->parts as $index => $part) {
            try{
                if($part->price == null || $part->price ==''){
                    $part->price=0;
                }
                $part->price=ceil(currency(floatval($part->price),null,null,false));
            }catch(HttpException $e){
                dd($part->price);
            }


            $result[$index] = $part;
            $result[$index]['created_at'] = date('d F Y H:i', strtotime($part['created_at']));
        }
        return $result;
    }

    ## Content Meeting ##
    public function contentMeetingItem($id)
    {
        $user = auth()->user();
        $dates = MeetingDate::where('user_id', $user->id)->where('content_id', $id)->get();
        $meetings = MeetingLink::where('user_id', $user->id)->where('content_id', $id)->get();
        $Content = Content::where('user_id', $user->id)->find($id);
        return view('web.default.user.content.meeting.list', [
            'dates' => $dates,
            'meetings' => $meetings,
            'id' => $id,
            'content' => $Content
        ]);
    }

    public function contentMeetingAction(Request $request)
    {
        $user = auth()->user();
        if ($request->action == 'zoom') {
            $Content = Content::where('user_id', $user->id)->find($request->content_id);
            if (!$Content)
                return ['status' => -1];

            $Zoom = zoomCreateMeeting($user->id, $Content->id, $Content->title, 60);
            return $Zoom;
        }
        if ($request->action == 'inactive') {
            $Content = Content::where('user_id', $user->id)->find($request->id);
            if (!$Content)
                return back()->with('msg', trans('admin.content_not_found'));

            $Content->update(['meeting_mode' => 'inactive']);
            return back()->with('msg', trans('main.successful'));
        }
        if ($request->action == 'active') {
            $Content = Content::where('user_id', $user->id)->find($request->id);
            if (!$Content)
                return back()->with('msg', trans('admin.content_not_found'));

            $Content->update([
                'meeting_type' => $request->type,
                'meeting_join_url' => $request->join_link,
                'meeting_start_url' => $request->start_link,
                'meeting_mode' => 'active',
                'meeting_password' => $request->meeting_password
            ]);

            return back()->with('msg', trans('main.successful'));
        }
    }

    public function contentMeetingNewStore($id, Request $request)
    {
        $user = auth()->user();
        $Content = Content::where('user_id', $user->id)->find($id);
        if (!$Content)
            return back()->with('msg', trans('main.access_denied_content'));

        $request->request->add(['content_id' => $id, 'user_id' => $user->id]);
        MeetingDate::create($request->all());

        return back();
    }

    public function contentMeetingDelete($id)
    {
        $user = auth()->user();
        MeetingDate::where('user_id', $user->id)->find($id)->delete();
        return back();
    }

    #################
    #### Tickets ####
    #################
    public function tickets()
    {
        $user = auth()->user();
        $ticket_invite = TicketsUser::where('user_id', $user->id)->pluck('ticket_id');
        $tickets = Tickets::with('category', 'messages')->orderBy('id', 'DESC')->where('user_id', $user->id)->orWhereIn('id', $ticket_invite->toArray())->get();
        $category = TicketsCategory::get();
        $data['type']='regular';
        $data['lists']=$tickets;
        $data['category']=$category;
        if(isset($_GET['type'])){
            $data['type']=$_GET['type'];
        }

        return view(getTemplate() . '.user.ticket.list', $data);

    }


    public function ticketStore(Request $request)
    {

        $user = auth()->user();

        $newTicketArray = [
            'title' => $request->title,
            'user_id' => $user->id,
            'created_at' => time(),
            'mode' => 'open',
            'category_id' => $request->category_id,
            'attach' => $request->attach
        ];

        $newTicket = Tickets::insertGetId($newTicketArray);

        $newMsgArray = [
            'ticket_id' => $newTicket,
            'msg' => $request->msg,
            'created_at' => time(),
            'user_id' => $user->id,
            'mode' => 'user',
            'attach' => $request->attach
        ];

        $newMsg = TicketsMsg::insert($newMsgArray);

        ## Notification Center
        //sendNotification(0, ['[t.title]' => $request->title], get_option('notification_template_ticket_new'), 'user', $user->id);

        return back();

    }



    public function ticketReply($id)
    {
        $user = auth()->user();
        $wherein = TicketsUser::where('user_id', $user->id)->where('ticket_id', $id)->pluck('ticket_id');
        $ticket = Tickets::with(['messages' => function ($q) {
            $q->orderBy('id', 'DESC');
        }])->where(function ($w) use ($user, $wherein) {
            $w->where('user_id', $user->id)->orwhereIn('id', $wherein->toArray());
        })->where('id', $id)->first();

        ## Update Notification
        foreach ($ticket->messages as $msgUpdate) {
            TicketsMsg::where('mode', '<>', 'user')->where('id', $msgUpdate->id)->update(['view' => 1]);
        }
        return view(getTemplate() . '.user.ticket.reply', ['ticket' => $ticket]);
    }

    public function ticketReplyStore(Request $request)
    {
        $user = auth()->user();
        $ticket = Tickets::find($request->ticket_id);
        $ticket_user = TicketsUser::where('ticket_id', $request->ticket_id)->where('user_id', $user->id)->first();
        if ($ticket->user_id == $user->id || $ticket_user) {
            $insertArray = [
                'created_at' => time(),
                'ticket_id' => $request->ticket_id,
                'attach' => $request->attach,
                'user_id' => $user->id,
                'mode' => 'user',
                'msg' => $request->msg
            ];
            TicketsMsg::insert($insertArray);
            if ($ticket->mode == 'close') {
                $ticket->update(['mode' => 'open']);
            }
        }
        return back();
    }

    public function ticketClose($id)
    {
        $user = auth()->user();
        $ticket = Tickets::where('user_id', $user->id)->find($id);
        $ticket->update(['mode' => 'close']);
        return back();
    }




    public function ticketComments(Request $request)
    {
        $user = auth()->user();
        $userContent = Content::where('user_id', $user->id)->where('mode', 'publish')->pluck('id')->toArray();
        $comments = ContentComment::with(['user', 'content'])->whereIn('content_id', $userContent)->Where('mode', 'publish')->orderBy('id', 'DESC');
        $count = $comments->count();
        if ($request->get('p', null) != null)
            $comments->skip($request->get('p', null) * 20);

        $comments->take(20);
        return view(getTemplate() . '.user.ticket.commentList', ['lists' => $comments->get(), 'count' => $count]);
    }

    public function ticketNotifications(Request $request)
    {
        $user = auth()->user();
        $notifications = Notification::where('recipent_list', $user->id)->orderBy('id', 'DESC')->get();
        $notificationLists = Notification::where('user_id', 0)->where('recipent_list', $user->id)->orderBy('id', 'DESC')->get();

        $count = $notificationLists->count();

        foreach ($notifications as $n) {

            notificationStatus($n->id, $user->id);

        }

        $results = Notification::where('user_id', 0)->where('recipent_list', $user->id)->update(['user_id' => 1]);

        return view(getTemplate() . '.user.ticket.notificationList', ['lists' => $notifications, 'count' => $count]);
    }
    
    public function notificationClear(Request $request)
    {
        $user = auth()->user();
        $notifications = Notification::whereIn('recipent_type', ['user', 'userone'])
            ->delete();
        $duplicate = NotificationStatus::where('user_id', $user->id)->delete();
        return back();
    }

    public function ticketSupport()
    {
        $user = auth()->user();
        $support = Content::with(['supports' => function ($q) {
            $q->with(['sender'])->where('mode', 'publish');
        }])->where('user_id', $user->id)->where('mode', 'publish')->get();
        return view(getTemplate() . '.user.ticket.supportList', ['supports' => $support]);
    }

    public function ticketSupportJson($content_id, $sender_id)
    {
        $user = auth()->user();
        if (!$user)
            return abort(404);

        $supports = ContentSupport::with(['sender' => function ($q) {
            $q->select('id', 'name', 'username');
        }])
            ->where('content_id', $content_id)
            ->where('sender_id', $sender_id)
            ->get();

        foreach ($supports as $index => $sup) {
            if ($sup->user_id != $sup->supporter_id && $sup->mode != 'publish')
                $supports->forget($index);
        }
        return $supports;
    }

    public function ticketSupportStore(Request $request)
    {
        $user = auth()->user();
        if (!$user)
            return abort(404);

        $content = Content::where('id', $request->content_id)->where('mode', 'publish')->where('user_id', $user->id)->first();
        if (!$content)
            return abort(404);

        $support = ContentSupport::create([
            'comment' => $request->comment,
            'user_id' => $user->id,
            'supporter_id' => $user->id,
            'sender_id' => $request->sender_id,
            'created_at' => time(),
            'name' => $user->name,
            'content_id' => $request->content_id,
            'rate' => '0',
            'mode' => 'draft'
        ]);

        if ($support->id)
            return $support;
    }

    ##############
    #### Sell ####
    ##############
    public function sellDownload(Request $request)
    {
        $user = auth()->user();

        if(isset($request->message)){
            $message=$request->message;
        }else{
            $message='';
        }

        if($user->type == 'Student'){
            $sellList=Balance::where('user_id', $user->id)->where('status','success')->orderBy('id', 'DESC');
            $count=$sellList->count();
            return view(getTemplate() . '.user.balance.balance', ['lists' => $sellList->get(),'count'=>$count,'message'=>$message]);
        }else {
            $sellList=Balance::where('user_id', $user->id)->orderBy('id', 'DESC');

            $sold_courses=Sell::where('seller_id',$user->id)->get();



            ## Update Notifications
            Sell::where('seller_id', $user->id)->where('type', 'download')->update(['view' => 1]);
            return view(getTemplate() . '.user.balance.balance', ['lists' => $sellList->get(),'user' => $user,'sold'=>$sold_courses]);
        }


    }

    public function sellPost(Request $request)
    {
        $user = auth()->user();
        $sellList = Sell::with(['buyer', 'content', 'transaction'])->where('user_id', $user->id)->where('type', 'post')->where(function ($q) {
            $q->where('post_code', null)->orwhere('post_code', '')->orWhere('post_confirm', '')->orWhere('post_confirm', null);
        })->get();
        $count = $sellList->count();
        if ($request->get('p') != null)
            $sellList->skip($request->get('p') * 20);
        $sellList->take(20);

        return view(getTemplate() . '.user.sell.post', ['lists' => $sellList, 'count' => $count]);
    }

    public function setPostalCode(Request $request)
    {
        $user = auth()->user();
        $Sell = Sell::where('user_id', $user->id)->find($request->sell_id);
        if (!$Sell)
            return redirect()->back()->with('msg', trans('main.failed_update'));

        if ($request->post_code == null)
            return redirect()->back()->with('msg', trans('main.parcel_tracking_code'));

        $Sell->post_code = $request->post_code;
        $Sell->post_code_date = time();
        $Sell->save();
        setNotification($user->id, 'sell', $request->sell_id);
        return redirect()->back()->with('msg', trans('main.parcel_tracking_success'));
    }

    public function balanceLogs(Request $request)
    {

        $user = auth()->user();
        $message='';
        if(isset($request->sess)){
            if(isset($request->m) && $request->m == 'success'){
                $key=encrypt_decrypt('decrypt',urldecode($request->sess));
                $check=Balance::where('token',$key)->where('status','pending')->get();
                if($check->count() == 1){
                    $newBalance = intval($user->credit) + intval($check[0]->price);
                    //$UpdateUser = User::where('id', $user->id)->update(['credit' => $newBalance]);
                    $UpdateLog = Balance::where('token', $key)->update(['status' => 'success','description'=>'Success ['.$key.']']);
                    $message='success';
                }else{
                    $message="expired";
                }
                return redirect('user/balance?tab=log&message='.$message);
            }
        }

    }

    public function balanceCharge()
    {
        $user = auth()->user();
        return view(getTemplate() . '.user.balance.charge', ['user' => $user]);
    }



    public function balanceReport(Request $request)
    {

        $user = auth()->user();
        $sells = Sell::with(['transaction'])->where('seller_id', $user->id)->where('mode', 'pay')->orderBy('created_at', 'DESC');
        if ($request->get('first_date') != null) {
            $first_date = strtotime($request->get('first_date'));
            $sells->where('created_at', '>', $first_date);
        } else {
            $first_date = Sell::with(['transaction'])->where('seller_id', $user->id)->where('mode', 'pay')->orderBy('created_at', 'DESC')->first();
            if ($first_date)
                $first_date = $first_date->created_at;
            else
                $first_date = time();
        }

        if ($request->get('last_date') != null) {
            $last_date = strtotime($request->get('last_date'));
            $sells->where('created_at', '<', $last_date);
        } else {
            $last_date = time();
        }
        $days = ($last_date - $first_date) / 86400;
        $prices = 0;
        $income = 0;
        foreach ($sells->get() as $stc) {
            $prices += $stc->transaction->price;
            $income += $stc->transaction->income;
        }

        for ($i = 1; $i < 13; $i++) {
            $curentYear = date('Y', time());
            $firstDate = mktime('12', '0', '0', $i, '1', $curentYear);
            $lastDate = mktime('12', '0', '0', $i + 1, '1', $curentYear);
            $chart['sell'][$i] = Sell::where('seller_id', $user->id)->where('mode', 'pay')->where('created_at', '>', $firstDate)->where('created_at', '<', $lastDate)->count();
            $chart['income'][$i] = Transaction::where('seller_id', $user->id)->where('mode', 'deliver')->where('created_at', '>', $firstDate)->where('created_at', '<', $lastDate)->sum('income');
        }

        return view(getTemplate() . '.user.balance.report', ['user' => $user, 'first_date' => $request->first_date, 'last_date' => $request->last_date, 'days' => $days, 'sellcount' => $sells->count(), 'prices' => $prices, 'income' => $income, 'chart' => $chart]);
    }

    ##############
    #### vimeo ####
    ##############
    function file_get_contents_chunked($file, $chunk_size, $callback)
    {
        try {
            $handle = fopen($file, "r");
            $i = 0;
            while (!feof($handle)) {
                call_user_func_array($callback, array(fread($handle, $chunk_size), &$handle, $i));
                $i++;
            }

            fclose($handle);

        } catch (Exception $e) {
            trigger_error("file_get_contents_chunked::" . $e->getMessage(), E_USER_NOTICE);
            return false;
        }

        return true;
    }

    public function vimeoDownload(Request $request)
    {
        $user = auth()->user();
        $link = $request->link;
        $Vimeo = new Vimeo();
        $downloadLink = $Vimeo->getVimeoDirectUrl($link);
        if (!file_exists(getcwd() . '/bin/' . $user['username']))
            mkdir(getcwd() . '/bin/' . $user['username']);
        file_put_contents(getcwd() . '/bin/' . $user['username'] . '/' . Str::random(16) . '.mp4', file_get_contents($downloadLink));
        return 'ok';
    }

    ## Become Vendor ##
    public function profileUpgrade()
    {

        return redirect('/user/ticket?type=upgrade');

    }


    public function userActive($token)
    {
        $user = User::where('token', $token)->first();
        $data['type']='';
        if ($user) {
            if($user->type == 'Teacher'){
                $user->update(['mode' => 'pending_manual_verification','token'=>'']);
                sendMail(['template' => get_option('teacher_pending_verification_email'), 'recipient' => [$user->email]]);
            }else if($user->type == 'Student'){
                $user->update(['mode' => 'active','token'=>'']);
                sendMail(['template' => get_option('student_register_welcome_email'), 'recipient' => [$user->email]]);
            }
            $data['type']=$user->type;

        } else {
            return abort(404);
        }

        return view(getTemplate() . '.auth.active',$data);
    }

    public function forgetPassword(Request $request)
    {
        $str = Str::random();
        $email = $request->get('email', null);

        if (!empty($email)) {
            $update = User::where('email', $email)->update(['token' => $str]);
            if ($update) {
                sendMail(['template' => get_option('user_register_reset_email'), 'recipient' => [$request->email]]);
                return back()->with('msg', trans('main.pass_change_link'));
            } else {
                return back()->with('msg', trans('main.user_not_found'));
            }
        }

        return back();
    }

    public function resetToken($token)
    {
        $password = Str::random(6);
        $user = User::where('token', $token)->first();
        $user->update(['password' => Hash::make($password)]);
        sendMail(['template' => get_option('user_register_new_password_email'), 'recipient' => [$user->email], 'password' => $password]);
        return redirect('/')->with('msg', trans('main.new_pass_email'));
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleDoLogin(Request $request)
    {
        session()->put('state', $request->input('state'));
        $user = Socialite::driver('google')->user();

        $newUser = [
            'username' => $user->name,
            'created_at' => time(),
            'admin' => 0,
            'email' => $user->email,
            'token' => $user->token,
            'password' => Hash::make(Str::random(10)),
            'mode' => 'active',
            'category_id' => get_option('user_default_category'),
        ];
        $ifUserExist = User::where('email', $newUser['email'])->first();

        if (empty($ifUserExist)) {
            $insertUser = User::create($newUser);
            Auth::login($insertUser);
            $request->session()->put('user', serialize($insertUser));
            return redirect('/user/profile');
        } else {
            $request->session()->put('user', serialize($ifUserExist->toArray()));
            Auth::login($ifUserExist);
            return redirect('/user/dashboard');
        }
    }

    ## Register Steps
    public function registerStepOne($phone)
    {
        $checkPhone = User::where('username', $phone)->count();
        if ($checkPhone > 0)
            return ['status' => 'error', 'description' => 'duplicate'];

        $random = random_int(11111, 99999);
        $newUser = User::create([
            'username' => $phone,
            'code' => $random,
            'admin' => 0,
            'created_at' => time()
        ]);
        if ($newUser) {
            sendSms($phone, $random);
            return ['status' => 'success', 'id' => $newUser->id];
        }
        return ['status' => 'error', 'description' => 'create'];
    }

    public function registerStepTwo($phone, $code)
    {
        $checkPhone = User::where('username', $phone)->where('mode', null)->where('password', null)->first();
        if (!$checkPhone || $checkPhone->code == null) {
            return ['status' => 'error', 'error' => '-1', 'description' => 'not found'];
        }
        if ($checkPhone->code != $code) {
            return ['status' => 'error', 'error' => '0', 'description' => 'code not correct'];
        }
        return ['status' => 'success'];
    }

    public function registerStepTwoRepeat($phone)
    {
        $checkPhone = User::where('username', $phone)->where('mode', null)->where('password', null)->first();
        if ($checkPhone) {
            $random = random_int(11111, 99999);
            $checkPhone->update(['code' => $random]);
            sendSms($phone, $random);
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'error' => '-1', 'description' => 'not found'];
    }

    public function registerStepThree($phone, $code, Request $request)
    {
        $checkPhone = User::where('username', $phone)->where('mode', null)->where('password', null)->first();
        if (!$checkPhone || $checkPhone->code == null) {
            return ['status' => 'error', 'error' => '-1', 'description' => 'not found'];
        }
        if ($checkPhone->code != $code) {
            return ['status' => 'error', 'error' => '0', 'description' => 'code not correct'];
        }

        if ($request->password != $request->repassword) {
            return ['status' => 'error', 'error' => '2', 'description' => 'password not same'];
        }

        $checkPhone->update([
            'password' => encrypt($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'mode' => 'active',
            'category_id' => get_option('user_default_category', 0),
            'token' => Str::random(15)
        ]);

        ## Send Suitable Email For New User ##
        /*
        if(get_option('user_register_mode') == 'deactive')
            sendMail(['template' => get_option('user_register_active_email'), 'recipient' => [$checkPhone->email]]);
        else
            sendMail(['template'=>get_option('user_register_wellcome_email'),'recipient'=>[$checkPhone->email]]);
        */

        $request->session()->put('user', serialize($checkPhone->toArray()));
        return ['status' => 'success'];
    }

    public function userFollow($id)
    {
        $user = auth()->user();
        if (empty($user)) {
            return redirect('/user');
        }

        $follow_count = Follower::where('user_id', $id)->where('follower', $user->id)->count();

        if ($follow_count > 0) {
            return back();
        } else {
            Follower::insert(['user_id' => $id, 'follower' => $user->id]);

            ## Notification Center
            sendNotification(0, ['[u.name]' => $user->name], get_option('notification_template_request_follow'), 'user', $id);

            return back();
        }
    }

    public function userUnFollow($id)
    {
        $user = auth()->user();
        if (empty($user)) {
            return redirect('/user');
        }
        Follower::where('user_id', $id)->where('follower', $user->id)->delete();
        return back();
    }

    ## Show Profile For All Users ##
    public function userProfileView($id)
    {
        $userContentsQuery = Content::where('user_id', $id)->where('mode', 'publish');

        $user = auth()->check() ? auth()->user() : false;

        $profile = User::where('id', $id)->first();
        if (empty($profile) || $profile->type== 'Student') {
            return redirect('/');
        }

        $oneToOne = Content::where('user_id', $id)->where('mode', 'publish')->where('type','1')->get();
        $groups = Content::where('user_id', $id)->where('mode', 'publish')->where('type','2')->get();
        $videos = Content::where('user_id', $id)->where('mode', 'publish')->where('type','3')->get();

        $vid_meta = array();
        foreach ($videos as $viid) {
            $vid_items=ContentPart::where('content_id',$viid->id)->get();
            $duration=0;
            if($vid_items->count() > 0){
                foreach($vid_items as $vid_item){
                    $duration += intval($vid_item->duration);
                }
            }
            $vid_meta[$viid->id]=array('total'=>$vid_items->count(),'duration'=>$duration);
        }

        $data = [
            'profile' => $profile,
            'videos' => $videos,
            'oneToOne' => $oneToOne,
            'groups' => $groups,
            'vid_meta' => $vid_meta,
            'meta' => arrayToList($profile->usermetas, 'option', 'value')
        ];
        if($oneToOne->count() > 0){
            $plans = ContentPart::where('content_id', $oneToOne[0]->id)->where('mode', 'publish')->get();
            $data['plans'] = $plans;
        }

        $data['reviews']=CourseFeedbackModel::where('teacher_id',$profile->id)->get();

        $data['agent'] = new Agent();

        return view(getTemplate() . '.view.profile.profile', $data);
    }

    public function profileRequestStore(Request $request)
    {
        $user = auth()->user();
        if ($user == null) {
            return redirect()->back()->with('msg', trans('main.login_request'));
        }

        Requests::create([
            'user_id' => $request->user_id,
            'requester_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'mode' => 'draft',
            'created_at' => time()
        ]);
        return redirect()->back()->with('msg', trans('main.req_success'));

    }

    private function saveUserAvatar($user, $image, $name)
    {
        $path = 'bin/media/users/' . $user->id;
        $img = \Image::make($image);

        if (!\File::exists($path)) {
            \File::makeDirectory($path);
        }

        $img_name = $user->username . '_' . $name . '.' . $image->getClientOriginalExtension();

        // save Main image
        $fileLocation = $path . "/" . $img_name;

        if (\File::exists(public_path($fileLocation))) {
            \File::delete([$fileLocation]);
        }

        $move = \File::put($fileLocation, (string)$img->encode());
        if ($move) {
            return $fileLocation;
        }
        return false;
    }


    ######################
    ### Bank Section ##
    ######################
    ## Paypal
    public function paypalPay($id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($content->title)
            ->setCurrency(currency())
            ->setQuantity(1)
            ->setPrice($Amount);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($Amount);
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Purchase Product');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(url('/') . '/bank/paypal/status')
            ->setCancelUrl(url('/') . '/bank/paypal/cancel/' . $id);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('paywithpaypal');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        $ids = $payment->getId();
        \Session::put('paypal_payment_id', $ids);
        Transaction::insert([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'paypal',
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'authority' => $ids,
            'type' => $mode
        ]);
        /** add payment ID to session **/
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');

    }

    public function paytmPay(Request $request, $id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $Transaction = Transaction::create([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'paytm',
            'authority' => 0,
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type' => $mode
        ]);

        $payment = PaytmWallet::with('receive');
        $payment->prepare([
            'order' => $Transaction->id,
            'user' => $user['id'],
            'email' => $user['email'],
            'mobile_number' => '00187654321',
            'amount' => $Transaction->price,
            'callback_url' => url('/') . '/bank/paytm/status/' . $content->id
        ]);
        return $payment->receive();

    }

    public function payuPay(Request $request, $id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        $strRnd = Str::random();
        $Transaction = Transaction::create([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'payu',
            'authority' => $strRnd,
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type' => $mode
        ]);


        $attributes = [
            'txnid' => $strRnd, # Transaction ID.
            'amount' => $Amount_pay, # Amount to be charged.
            'productinfo' => $content->title,
            'firstname' => "John", # Payee Name.
            'email' => "john@doe.com", # Payee Email Address.
            'phone' => "9876543210", # Payee Phone Number.
            'surl' => url('/') . '/bank/payu/status/' . $content->id,
            'furl' => url('/') . '/bank/payu/status/' . $content->id,
        ];

        return \Tzsk\Payu\Facade\Payment::make($attributes, function ($then) use ($content) {
            $then->redirectTo(url('/') . '/bank/payu/status/' . $content->id);
        });
    }

    public function paystackPay(Request $request, $id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];

        $Transaction = Transaction::create([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'paystack',
            'authority' => 0,
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type' => $mode
        ]);
        $payStack = new \Unicodeveloper\Paystack\Paystack();
        $payStack->getAuthorizationResponse([
            "amount" => $Amount_pay,
            "reference" => Paystack::genTranxRef(),
            "email" => $user['email'],
            "callback_url" => url('/') . '/bank/paystack/status/' . $content->id,
            'metadata' => json_encode(['transaction' => $Transaction->id])
        ]);
        return redirect($payStack->url);
    }

    public function razorpayPay(Request $request, $id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];

        $Transaction = Transaction::create([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'razorpay',
            'authority' => 0,
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type' => $mode
        ]);

        $razorpay = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $order = $razorpay->order->create(['receipt' => $Transaction->id, 'amount' => $Transaction->price * 100, 'currency' => 'INR']);
        $Transaction->update(['authority' => $order['id']]);
        return '<form action="' . url('/') . '/bank/razorpay/status/' . $content->id . '" method="POST">
                    <script
                        src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="' . env('RAZORPAY_KEY_ID') . '"
                        data-amount="' . $Transaction->price * 100 . '"
                        data-currency="INR"
                        data-order_id="' . $order['id'] . '"
                        data-buttontext="Pay with Razorpay"
                        data-name=""
                        data-description=""
                        data-image=""
                        data-prefill.name=""
                        data-prefill.email=""
                        data-theme.color="#F37254"></script>
                       <input type="hidden" name="_token" value="' . csrf_token() . '">
                    </form>';
    }

    public function mpesaPay(Request $request, $id, $mode = 'download')
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $Transaction = Transaction::create([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'created_at' => time(),
            'bank' => 'mpesa',
            'authority' => 0,
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type' => $mode
        ]);

    }

    public function wecashupPay(Request $request, $id, $mode = 'download'){
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $Transaction = Transaction::create([
            'buyer_id'      => $user['id'],
            'user_id'       => $content->user_id,
            'content_id'    => $content->id,
            'price'         => $Amount_pay,
            'price_content' => $Amount,
            'mode'          => 'pending',
            'created_at'    => time(),
            'bank'          => 'wecashup',
            'authority'     => 0,
            'income'        => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type'          => $mode
        ]);

        echo '<form action="https://academy.prodevelopers.eu/bank/wecashup/callback" method="POST" id="wecashup">

        <script async src="https://www.wecashup.com/library/MobileMoney.js" class="wecashup_button"
        data-demo
        data-sender-lang="en"
        data-sender-phonenumber=""
        data-receiver-uid="'.env('Merchant_UID').'"
        data-receiver-public-key="'.env('Merchant_Public_Key').'"
        data-transaction-parent-uid=""
        data-transaction-receiver-total-amount="'.($Amount_pay).'"
        data-transaction-receiver-reference="'.$Transaction->id.'"
        data-transaction-sender-reference="'.$Transaction->id.'"
        data-sender-firstname="Test"
        data-sender-lastname="Test"
        data-transaction-method="pull"
        data-image="'.url('/').get_option('site_logo').'"
        data-name="'.$content->title.'"
        data-crypto="true"
        data-cash="true"
        data-telecom="true"
        data-m-wallet="true"
        data-split="true"
        configuration-id="3"
        data-marketplace-mode="false"
        data-product-1-name="'.$content->title.'"
        data-product-1-quantity="1"
        data-product-1-unit-price="'.($Amount_pay).'"
        data-product-1-reference="'.$Transaction->id.'"
        data-product-1-category="Billeterie"
        data-product-1-description="'.$content->title.'"
        >
        </script>
</form>';
    }

    public function cinetpayPay(Request $request, $id, $mode = 'download'){
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        ## Vendor Group Percent
        $Vendor = User::with(['category'])->find($content->user_id);
        if (isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)) {
            $site_income = $site_income - $Vendor->category->commision;
        }

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($mode == 'download')
            $Amount = $meta['price'];
        elseif ($mode == 'post')
            $Amount = $meta['post_price'];

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required
        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        ## Vendor Rate Percent
        if ($Vendor) {
            $Rates = getRate($Vendor->toArray());
            if ($Rates) {
                $RatePercent = 0;
                foreach ($Rates as $rate) {
                    $RatePercent += $rate['commision'];
                }

                $site_income = $site_income - $RatePercent;
            }
        }

        $Transaction = Transaction::create([
            'buyer_id'      => $user['id'],
            'user_id'       => $content->user_id,
            'content_id'    => $content->id,
            'price'         => $Amount_pay,
            'price_content' => $Amount,
            'mode'          => 'pending',
            'created_at'    => time(),
            'bank'          => 'cinetpay',
            'authority'     => 0,
            'income'        => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'type'          => $mode
        ]);

        try {
            $id_transaction     = $Transaction->id;
            $description        = $content->title;
            $date_transaction   = date("Y-m-d H:i:s");
            $amount             = $Amount_pay;
            $payer_identify     = $Transaction->user->email;
            $apiKey             = env('CINET_API_KEY');
            $site_id            = env('CINET_SITE_ID');
            $platform           = "PROD";
            $version            = "V2";
            $formName           = "goCinetPay";
            $notify_url         = url('/').'/bank/cinetpay/notify';
            $return_url         = url('/').'/bank/cinetpay/return';
            $cancel_url         = url('/').'/bank/cinetpay/cancel';
            $btnType            = 2;
            $btnSize            = 'larger';
            $CinetPay = new CinetPay($site_id,$apiKey,$platform,$version);
            $CinetPay->setTransId($id_transaction)
                ->setDesignation($description)
                ->setTransDate($date_transaction)
                ->setAmount($amount)
                ->setDebug(false)// put it on true, if you want to activate debug
                ->setCustom($payer_identify)// optional
                ->setNotifyUrl($notify_url)// optional
                ->setReturnUrl($return_url)// optional
                ->setCancelUrl($cancel_url)// optional
                ->displayPayButton($formName, $btnType, $btnSize);
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    // *******
    // QuizzesList
    public function QuizzesList()
    {
        $user = auth()->user();

        $quizzesQuery = Quiz::query();

        if ($user->type=='Teacher') {
            $quizzes = $quizzesQuery->where('user_id', $user->id)
                ->with(['questions', 'content', 'QuizResults' => function ($query) {
                    $query->orderBy('status', 'desc');
                    $query->with('student');
                }])->get();

            foreach ($quizzes as $quiz) {
                $QuizResults = $quiz->QuizResults;
                $waiting_results = 0;
                $passed_results = 0;
                $total_grade = 0;
                foreach ($QuizResults as $result) {
                    if ($result->status == 'waiting') {
                        $waiting_results += 1;
                    } else if ($result->status == 'pass') {
                        $passed_results += 1;
                    }
                    $total_grade += (int)$result->user_grade;
                }

                $quiz->average_grade = ($total_grade > 0) ? round($total_grade / count($QuizResults), 2) : 0;
                $quiz->review_needs = $waiting_results;
            }
        } else {
            $quizzes = $quizzesQuery->where('status', 'active')
                ->with(['questionsGradeSum', 'content'])
                ->get();

            foreach ($quizzes as $quiz) {
                $quizResults = QuizResult::where('student_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->orderBy('id', 'desc')
                    ->get();

                $quiz->result = $quizResults->first();
                $quiz->result_count = count($quizResults);

                $quiz->can_try = true;
                if ((isset($quiz->attempt) and count($quizResults) >= $quiz->attempt) or (!empty($quiz->result) and $quiz->result->status === 'pass')) {
                    $quiz->can_try = false;
                }
            }
        }

        $data = [
            'user' => $user,
            'quizzes' => $quizzes,
        ];

        return view(getTemplate() . '.user.quizzes.list', $data);
    }

    public function QuizzesStore(Request $request)
    {
        $user = auth()->user();
        $data = $request->except('_token');
        $rules = [
            'name' => 'required',
            'content_id' => 'required|numeric',
            'pass_mark' => 'required|numeric',
        ];
        $this->validate($request, $rules);

        $data['user_id'] = $user->id;
        $data['created_at'] = time();

        $quiz = Quiz::create($data);

        if ($quiz) {
            return redirect()->back()->with('msg', trans('main.quiz_created_msg'));
        }

        return redirect()->back()->with('msg', trans('main.failed_store'));
    }

    public function QuizzesEdit($quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)->where('user_id', $user->id)->first();
        if (!empty($quiz)) {

            $data['course']=Content::where('id',$quiz->content_id)->first();
            $data['class']=ClassModel::where('id',$quiz->class_id)->first();
            $data['user']= $user;
            $data['quiz']= $quiz;
            return view(getTemplate() . '.user.quizzes.list', $data);
        }

        abort(404);
    }

    public function QuizzesUpdate(Request $request, $quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($quiz)) {
            $data = $request->except('_token');
            $rules = [
                'name' => 'required',
                'content_id' => 'required|numeric',
                'pass_mark' => 'required|numeric',
            ];
            $this->validate($request, $rules);

            $results = QuizResult::where('quiz_id', $quiz->id)->get();
            foreach ($results as $result) {
                if ($result->user_grade >= $quiz->pass_mark) {
                    $result->status = 'pass';
                    $result->save();
                }
            }

            $data['updated_at'] = time();
            $quiz->update($data);
            return redirect('user/homework/class?id='.$quiz->class_id)->with('msg', trans('main.quiz_updated_msg'));
        }

        return back();
    }

    public function QuizzesDelete($quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($quiz)) {
            $quiz->delete();
            return back()->with('msg', trans('main.quiz_delete_msg'));
        }

        abort(404);
    }

    public function QuizzesQuestions($quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)->where('user_id', $user->id)->first();

        if (!empty($quiz)) {
            $data['course']=Content::where('id',$quiz->content_id)->first();
            $data['class']=ClassModel::where('id',$quiz->class_id)->first();
            $data['user']= $user;
            $data['quiz']= $quiz;
            return view(getTemplate() . '.user.quizzes.questions', $data);
        }

        abort(404);
    }

    public function QuizzesQuestionsStore(Request $request, $quiz_id)
    {

        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($quiz)) {
            $rules = [
                'title' => 'required',
                'grade' => 'required',
            ];
            $this->validate($request, $rules);

            $data = $request->except(['_token']);
            if(!isset($data['file'])){
                $data['file']='';
            }
            $question_data = [
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'title' => $data['title'],
                'grade' => $data['grade'],
                'type' => $data['type'],
                'file' => $data['file'],
                'created_at' => time(),
            ];
            $question = QuizzesQuestion::create($question_data);
            if ($question) {
                if (!empty($data['answers']) and count($data['answers'])) {
                    foreach ($data['answers'] as $answer) {
                        QuizzesQuestionsAnswer::create([
                            'user_id' => $user->id,
                            'question_id' => $question->id,
                            'title' => $answer['title'],
                            'image' => $answer['image'],
                            'correct' => $answer['correct'],
                            'created_at' => time(),
                        ]);
                    }
                }

                return back()->with('msg', trans('main.question_create_msg'));
            }
        }

        abort(404);
    }

    public function QuizzesStart($quiz_id)
    {
        $user = auth()->user();

        $quiz = Quiz::where('id', $quiz_id)
            ->with(['questions' => function ($query) {
                $query->with(['questionsAnswers']);
            }, 'questionsGradeSum'])
            ->first();

        if ($quiz) {
            $attempt_count = $quiz->attempt;
            $userQuizDone = QuizResult::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->get();
            $status_pass = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == 'pass') {
                    $status_pass = true;
                }
            }

            if (!isset($quiz->attempt) or (count($userQuizDone) < $attempt_count and !$status_pass)) {
                $newQuizStart = QuizResult::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $user->id,
                    'results' => '',
                    'user_grade' => '',
                    'status' => 'waiting',
                    'created_at' => time()
                ]);

                $data = [
                    'quiz' => $quiz,
                    'newQuizStart' => $newQuizStart
                ];

                return view(getTemplate() . '.user.quizzes.start', $data);
            } else {
                return back()->with('msg', trans('main.cant_start_quiz'));
            }
        }
        abort(404);
    }

    public function QuizzesStoreResult(Request $request, $quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)->first();
        if ($quiz) {
            $results = $request->get('question');
            $quiz_result_id = $request->get('quiz_result_id');

            if (!empty($quiz_result_id)) {
                $quiz_result = QuizResult::where('id', $quiz_result_id)
                    ->where('student_id', $user->id)
                    ->first();

                if (!empty($quiz_result)) {
                    $pass_mark = $quiz->pass_mark;
                    $total_mark = 0;
                    $status = '';

                    foreach ($results as $question_id => $result) {
                        if (!is_array($result)) {
                            unset($results[$question_id]);
                        } else {
                            $question = QuizzesQuestion::where('id', $question_id)
                                ->where('quiz_id', $quiz->id)
                                ->first();
                            if ($question and !empty($result['answer'])) {
                                $answer = QuizzesQuestionsAnswer::where('id', $result['answer'])
                                    ->where('question_id', $question->id)
                                    ->where('user_id', $quiz->user_id)
                                    ->first();

                                $results[$question_id]['status'] = false;
                                $results[$question_id]['grade'] = $question->grade;

                                if ($answer and $answer->correct) {
                                    $results[$question_id]['status'] = true;
                                    $total_mark += (int)$question->grade;
                                }

                                if ($question->type == 'descriptive') {
                                    $status = 'waiting';
                                    //$total_mark += (int)$question->grade;
                                }
                            }
                        }
                    }

                    if (empty($status)) {
                        $status = ($total_mark >= $pass_mark) ? 'pass' : 'fail';
                    }

                    $quiz_result->update([
                        'results' => json_encode($results),
                        'user_grade' => $total_mark,
                        'status' => $status,
                        'created_at' => time()
                    ]);

                    return redirect('/user/quizzes/results/' . $quiz_result->id);
                }
            }
        }
        abort(404);
    }

    public function StudentQuizzesResults($result_id)
    {
        $user = auth()->user();
        $quiz_result = QuizResult::where('id', $result_id)
            ->where('student_id', $user->id)
            ->with(['quiz' => function ($query) {
                $query->with(['questions', 'questionsGradeSum']);
            }])
            ->first();

        if ($quiz_result) {
            $quiz = $quiz_result->quiz;
            $attempt_count = $quiz->attempt;
            $userQuizDone = QuizResult::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->count();

            $canTryAgain = false;
            if ($userQuizDone < $attempt_count) {
                $canTryAgain = true;
            }

            $data = [
                'quiz_result' => $quiz_result,
                'quiz' => $quiz,
                'canTryAgain' => $canTryAgain,
            ];
            return view(getTemplate() . '.user.quizzes.student_results', $data);
        }
        abort(404);
    }

    public function QuizzesResults($quiz_id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $quiz_id)
            ->where('user_id', $user->id)
            ->with(['content', 'questions', 'QuizResults' => function ($query) {
                $query->orderBy('status', 'desc');
                $query->with('student');
            }])
            ->first();

        if ($quiz) {
            $QuizResults = $quiz->QuizResults;
            $waiting_results = 0;
            $passed_results = 0;
            $total_grade = 0;
            foreach ($QuizResults as $result) {
                if ($result->status == 'waiting') {
                    $waiting_results += 1;
                } else if ($result->status == 'pass') {
                    $passed_results += 1;
                }
                $total_grade += (int)$result->user_grade;
            }

            $hasDescriptive = false;
            foreach ($quiz->questions as $question) {
                if ($question->type == 'descriptive') {
                    $hasDescriptive = true;
                }
            }

            $quiz->hasDescriptive = $hasDescriptive;

            $data = [
                'quiz' => $quiz,
                'QuizResults' => $QuizResults,
                'waitingResults' => $waiting_results,
                'passedResults' => $passed_results,
                'averageResults' => ($total_grade > 0) ? round($total_grade / count($QuizResults), 2) : 0,
            ];

            return view(getTemplate() . '.user.quizzes.results', $data);
        }
        abort(404);
    }

    public function QuizzesResultsDescriptive(Request $request)
    {
        $user = auth()->user();
        $result_id = $request->get('result_id');
        if ($result_id) {
            $descriptives = [];
            $QuizResult = QuizResult::findOrFail($result_id);
            $results = json_decode($QuizResult->results);

            if (!empty($results)) {
                foreach ($results as $question_id => $result) {
                    $question = QuizzesQuestion::where('id', $question_id)
                        ->where('user_id', $user->id)
                        ->first();
                    if (!empty($question) and ($question->type == 'descriptive' || $question->type == 'audio')) {
                        if($question->type == 'audio'){
                            $result->answer='<a href="'.url($result->answer).'" target="_blank" class="btn btn-sm btn-primary">View File</a>';
                        }

                        $item = [
                            'question_id' => $question->id,
                            'question' => $question->title,
                            'question_grade' => $question->grade,
                            'result_grade' => (!empty($result->grade)) ? $result->grade : '',
                            'result_status' => $QuizResult->status,
                            'answer' => !empty($result->answer) ? $result->answer : ''
                        ];
                        $descriptives[] = $item;
                    }
                }
            }

            return response()->json([
                'data' => $descriptives,
            ], 200);
        }
    }

    public function QuizzesResultsReviewed(Request $request)
    {
        $user = auth()->user();
        $result_id = $request->get('result_id');

        if ($result_id) {
            $quizResult = QuizResult::findOrFail($result_id);
            $results = json_decode($quizResult->results);
            $reviews = $request->get('review');
            $user_grade = $quizResult->user_grade;

            foreach ($results as $question_id => $result) {
                foreach ($reviews as $question_id2 => $review) {
                    if ($question_id2 == $question_id) {
                        $question = QuizzesQuestion::where('id', $question_id)
                            ->where('user_id', $user->id)
                            ->first();

                        if (!empty($result->status) and $result->status) {
                            $user_grade = $user_grade - (isset($result->grade) ? (int)$result->grade : 0);
                            $user_grade = $user_grade + (isset($review['grade']) ? (int)$review['grade'] : (int)$question->grade);
                            $result->grade = isset($review['grade']) ? $review['grade'] : $question->grade;
                        } else if (isset($result->status) and !$result->status) {
                            $user_grade = $user_grade + (isset($review['grade']) ? (int)$review['grade'] : (int)$question->grade);
                            $result->grade = isset($review['grade']) ? $review['grade'] : $question->grade;
                        }

                        $result->status = true;
                    }
                }
            }

            $quizResult->user_grade = $user_grade;

            $pass_mark = $quizResult->quiz->pass_mark;

            if ($quizResult->user_grade >= $pass_mark) {
                $quizResult->status = 'pass';
            } else {
                $quizResult->status = 'fail';
            }

            $quizResult->results = json_encode($results);

            $quizResult->save();

            return back()->with('msg', trans('main.review_success'));
        }
        abort(404);
    }

    public function QuizzesDownloadCertificate($result_id)
    {
        $user = auth()->user();

        $result = QuizResult::where('id', $result_id)
            ->where('student_id', $user->id)
            ->where('status', 'pass')
            ->with(['quiz' => function ($query) {
                $query->with(['content']);
            }])
            ->first();

        if ($result and !empty($result->quiz)) {
            $quiz = $result->quiz;
            $certificateTemplate = CertificateTemplate::where('status', 'publish')->first();

            $img = Image::make(getcwd() . $certificateTemplate->image);
            $body = $certificateTemplate->body;
            $body = str_replace('[user]', $user->name, $body);
            $body = str_replace('[course]', $quiz->content->title, $body);
            $body = str_replace('[grade]', $result->user_grade, $body);

            $img->text($body, $certificateTemplate->position_x, $certificateTemplate->position_y, function ($font) use ($certificateTemplate) {
                $font->file(getcwd() . '/assets/admin/fonts/nunito-v9-latin-regular.ttf');
                $font->size($certificateTemplate->font_size);
                $font->color($certificateTemplate->text_color);
            });
            //return $img->response('png');

            $path = getcwd() . '/bin/' . $user->username . '/certificates';

            if (!is_dir($path)) {
                mkdir($path);
            }

            $file_path = $path . '/' . $quiz->content->title . '(' . $quiz->name . ').jpg';
            if (is_file($file_path)) {
                $file_path = $path . '/' . $quiz->content->title . '(' . $quiz->name . '-' . $result->user_grade . ').jpg';
            }

            $img->save($file_path);

            $certificate = Certificate::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->where('quiz_result_id', $result->id)
                ->first();

            $data = [
                'quiz_id' => $quiz->id,
                'student_id' => $user->id,
                'quiz_result_id' => $result->id,
                'user_grade' => $result->user_grade,
                'file' => $file_path,
                'created_at' => time()
            ];

            if (!empty($certificate)) {
                $certificate->update($data);
            } else {
                Certificate::create($data);
            }

            if (file_exists($file_path)) {
                return response()->download($file_path);
            }
        }


        abort(404);
    }

    public function QuizzesQuestionsEdit(Request $request, $question_id)
    {
        $user = auth()->user();
        $question = QuizzesQuestion::where('id', $question_id)
            ->where('user_id', $user->id)
            ->first();
        $html = '';
        $status = false;

        if (!empty($question)) {
            $quiz = Quiz::find($question->quiz_id);
            if (!empty($quiz)) {
                $status = true;
                $data = [
                    'quiz' => $quiz,
                    'question_edit' => $question
                ];

                if ($question->type == 'multiple') {
                    $html = (string)\View::make(getTemplate() . '.user.quizzes.multiple_question_form', $data);
                } else {
                    $html = (string)\View::make(getTemplate() . '.user.quizzes.descriptive_question_form', $data);
                }
            }
        }

        return response()->json([
            'status' => $status,
            'html' => $html
        ], 200);
    }

    public function QuizzesQuestionsUpdate(Request $request, $question_id)
    {
        $user = auth()->user();
        $question = QuizzesQuestion::where('id', $question_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($question)) {
            $quiz = Quiz::find($question->quiz_id);
            if (!empty($quiz)) {
                $rules = [
                    'title' => 'required',
                    'grade' => 'required',
                ];
                $this->validate($request, $rules);
                $data = $request->except(['_token']);

                $question->update([
                    'title' => $data['title'],
                    'grade' => $data['grade'],
                    'updated_at' => time(),
                ]);

                QuizzesQuestionsAnswer::where('user_id', $user->id)
                    ->where('question_id', $question->id)
                    ->delete();

                if (!empty($data['answers']) and count($data['answers'])) {
                    foreach ($data['answers'] as $answer) {
                        QuizzesQuestionsAnswer::create([
                            'user_id' => $user->id,
                            'question_id' => $question->id,
                            'title' => $answer['title'],
                            'image' => $answer['image'],
                            'correct' => $answer['correct'],
                            'created_at' => time(),
                        ]);
                    }
                }

                return back()->with('msg', trans('main.question_create_msg'));
            }
        }

        abort(404);
    }

    public function QuizzesQuestionsDelete($question_id)
    {
        $user = auth()->user();
        $question = QuizzesQuestion::where('id', $question_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($question)) {
            $question->delete();
            return back()->with('msg', trans('main.question_delete_msg'));
        }

        abort(404);
    }

    public function CertificatesLists()
    {
        $user = auth()->user();
        $certificates = QuizResult::where('student_id', $user->id)
            ->where('status', 'pass')
            ->with(['quiz' => function ($query) {
                $query->with(['content']);
            }])
            ->get();

        $data = [
            'certificates' => $certificates,
        ];
        return view(getTemplate() . '.user.certificates.lists', $data);
    }


    ## Video Live ##
    public function videoLiveList()
    {
        $user = auth()->user();
        $courses = Content::where('mode', 'publish')->where('user_id', $user->id)->where(function ($w) {
            $w->where('type', 'webinar')->orWhere('type', 'course+webinar');
        })->get();
        $list = MeetingDate::with(['content'])->where('user_id', $user->id)->paginate(20);
        return view('web.default.user.meeting.list', [
            'courses' => $courses,
            'list' => $list
        ]);
    }

    public function videoLiveNewStore(Request $request)
    {
        $user = auth()->user();
        $Content = Content::where('user_id', $user->id)->find($request->content_id);
        if (!$Content)
            return back()->with('msg', trans('main.access_denied_content'));

        $timeStart = strtotime($request->date . ' ' . $request->time);
        $timeEnd = $timeStart + ($request->duration * 3600);
        $request->request->add([
            'user_id' => $user->id,
            'time_start' => $timeStart,
            'time_end' => $timeEnd
        ]);
        MeetingDate::create($request->all());

        return back();
    }

    public function videoLiveEditStore($id, Request $request)
    {
        $user = auth()->user();
        $Content = Content::where('user_id', $user->id)->find($request->content_id);
        if (!$Content)
            return back()->with('msg', trans('main.access_denied_content'));

        $timeStart = strtotime($request->date . ' ' . $request->time);
        $timeEnd = $timeStart + ($request->duration * 3600);
        $request->request->add([
            'user_id' => $user->id,
            'time_start' => $timeStart,
            'time_end' => $timeEnd
        ]);
        MeetingDate::find($id)->update($request->all());
        return back();
    }

    public function videoLiveEdit($id)
    {
        $user = auth()->user();
        $courses = Content::where('mode', 'publish')->where('user_id', $user->id)->where(function ($w) {
            $w->where('type', 'webinar')->orWhere('type', 'course+webinar');
        })->get();
        $list = MeetingDate::with(['content'])->where('user_id', $user->id)->paginate(20);
        $edit = MeetingDate::where('user_id', $user->id)->find($id);
        return view('web.default.user.meeting.list', [
            'courses' => $courses,
            'list' => $list,
            'edit' => $edit
        ]);
    }

    public function videoLiveUsers($id)
    {
        $user = auth()->user();
        $course = Content::where('user_id', $user->id)->find($id);
        if (!$course)
            return back();

        $list = Sell::with(['buyer'])->where('content_id', $id);

        return view('web.default.user.meeting.users', ['list' => $list->paginate(30)]);
    }

    public function videoLiveUrlStore($id, Request $request)
    {
        $user = auth()->user();
        MeetingDate::where('user_id', $user->id)->find($id)->update($request->all());
        return back()->with('msg', trans('main.successful'));

    }

    public function dailyFeedback(Request $request){
        $user=Auth::user();

        if(isset($request->submit)){
            $data['score']=$request->score;
            $id=$request->token_id;
            $data['completed']=1;

            if(intval($request->score) > 3){
                $data['remarks']=$request->feedback45;
            }else{
                if($request->any_problems == 'No'){
                    $data['remarks']=$request->feedback13;

                }else{
                    $data['remarks']=$request->feedback13Negative;
                    $data['refund_requested']=$request->refund;
                    $data['issue']=$request->problem;
                }
            }
            $events = ClassModel::where('id', $id)->where('booking_user_id',$user->id)->update($data);
            return redirect('/user/dashboard');

        }else{
            if($user->type != 'Teacher'){
                $today=strtotime(date('Y-m-d'));
                $events=ClassModel::where('timeval','<',$today)->where('completed','0')->where('booking_user_id',$user->id)->get();

                $today=date('Y-m-d');
                foreach($events as $event){
                    $only_event_date=substr($event->start, 0, 10);
                    if(strtotime($today) > strtotime($only_event_date) && $event->completed == 0){
                        $startHour = substr($event->start, -9,-4);
                        $endHour = substr($event->end, -9,-4);
                        $pending_feedback=['id'=>$event->id,'teacher'=>get_username($event->user_id),'title'=>$event->title,'date'=>$only_event_date,'time'=>$startHour.'-'.$endHour];
                        $data['pending_feedback']=$pending_feedback;
                        return view(getTemplate() . '.user.pages.dailyFeedback', $data);
                    }
                }
            }
        }

    }

    public function courseFeedback(Request $request){
        $user=Auth::user();

        if(isset($request->submit)){
            $id=$request->token_id;

            $sell=Sell::where('id',$request->sell_id)->first();

            $feedback_update = CourseFeedbackModel::updateOrCreate(
                ['teacher_id' => $sell->seller_id, 'student_id' => $sell->buyer_id,'course_id'=>$sell->content_id],
                ['feedback' => $request->feedback45,'score'=>$request->score]
            );
            $sell_update=Sell::where('id',$request->sell_id)->update(['status'=>'completed']);

            return redirect('/user/dashboard');

        }else{
            if($user->type != 'Teacher'){
                $courses=Sell::where('buyer_id',$user->id)->where('status','ongoing')->get();
                if($courses->count() > 0){
                    $events = ClassModel::where('booking_user_id', $user->id)
                        ->where('course_id',$courses[0]->content_id)
                        ->where('completed',0)
                        ->get();
                    if($events->count() == 0){
                        $pending_feedback=['sell_id'=>$courses[0]->id,'id'=>$courses[0]->content_id,'teacher'=>get_username($courses[0]->seller_id),'title'=>get_courseName($courses[0]->content_id,false)];
                        $data['pending_feedback']=$pending_feedback;
                        return view(getTemplate() . '.user.pages.courseFeedback', $data);

                    }
                }
            }
        }

    }

    public function publishCourse(Request $request){
        $id=$request->course_id;
        $content=Content::where('id',$id)->first();
        $schedule=Schedule::where('course_id',$content->id)->get()->count();
        if($content->mode != 'publish' && $schedule > 0){
            $content=Content::where('id',$id)->update(['mode'=>'request']);
            return back()->with('msg', trans('Your request to publish this course has been sent to content review department.'));
        }else{
            return back()->with('msg', trans('You do not have any schedule. Please add your schedules to publish the course'));
        }
    }



    public function getClassSchedules($user){
        $events=[];
        $one_course_ids=[];
        $one_course_part_ids=[];
        $group_course_ids=[];
        if($user->type == 'Teacher') {
            $sells = Sell::where('seller_id', $user->id)->where('status', 'ongoing')->get();
        }else {
            $sells=Sell::where('buyer_id',$user->id)->where('status','ongoing')->get();
        }

            foreach($sells as $sell){
                $check_content=Content::where('id',$sell->content_id)->get();
                if($check_content->count() > 0){
                    if($check_content[0]->type== '1'){
                        $one_course_ids[]=$sell->content_id;
                        $one_course_part_ids[]=$sell->content_part_id;
                    }else if($check_content[0]->type== '2'){
                        $group_course_ids[]=$sell->content_id;
                    }
                }
            }


        $today=strtotime(date('Y-m-d'));
        if($user->type == 'Teacher') {
            $one2one_classes = ClassModel::where('user_id', $user->id)
                ->where('status', 'booked')
                ->whereIn('course_id', $one_course_ids)
                ->whereIn('part_id', $one_course_part_ids)
                ->where('completed', '!=', '1')
                ->where('timeval', '>=', $today)
                ->orderBy('start')
                ->get();

            $group_classes = ClassModel::where('user_id', $user->id)
                ->where('status', 'booked')
                ->whereIn('course_id', $group_course_ids)
                ->where('completed', '!=', '1')
                ->where('timeval', '>=', $today)
                ->orderBy('start')
                ->get();
        }else{
            $one2one_classes = ClassModel::where('booking_user_id', $user->id)
                ->where('status', 'booked')
                ->whereIn('course_id', $one_course_ids)
                ->whereIn('part_id', $one_course_part_ids)
                ->where('completed', '!=', '1')
                ->whereDate(DB::raw(substr('start', 0, 10)), '>=', date('Y-m-d'))
                // ->where('timeval', '>=', $today)
                ->orderBy('start')
                ->get();

            $group_classes = ClassModel::where('status', 'booked')
                ->whereIn('course_id', $group_course_ids)
                ->where('completed', '!=', '1')
                ->where('timeval', '>=', $today)
                ->orderBy('start')
                ->get();
        }
        $events['one']=$one2one_classes;
        $events['group']=$group_classes;
        return $events;
    }


    public function getCompletedSchedules($user){
        $events=[];
        if($user->type == 'Teacher'){
            $events = ClassModel::where('user_id', $user->id)
                ->where('completed', '1')
                ->orderBy('start')
                ->get();
        }else{
            $events = ClassModel::where('booking_user_id', $user->id)
                // ->where('status', 'booked')
                // ->where('timeval', '<', $today)
                ->whereDate(DB::raw(substr('start', 0, 10)), '<', date('Y-m-d'))
                ->orderBy('start')
                ->get();
        }
        return $events;
    }

    public function addToFavourite(Request $request){
        $user=Auth::user();
        $teacher_id=$request->input('id');
        $teacher=User::where('id',$teacher_id)->get();
        if($user->type =='Student'){
            if($teacher->count() == 1){
                $fav=Favorite::where('seller_id',$teacher_id)->where('user_id',$user->id)->get();
                if($fav->count() > 0){
                    return back()->with('msg', trans('Already in your favourite list'));
                }else{
                    $add_fav=Favorite::create(['seller_id'=>$teacher_id,'user_id'=>$user->id]);
                    return redirect('/user/favourites');
                }
            }else{
                return back()->with('msg', trans('Unable to find the teacher account'));
            }
        }else{
            return back()->with('msg', trans('Can not add to your favourite list.[Reason:Teacher Account]'));
        }

    }


    public function favourites(Request $request){
        $user=Auth::user();
        $data['favs']=Favorite::where('user_id',$user->id)->get();
        return view(getTemplate() . '.user.sell.favourite', $data);
    }

}
