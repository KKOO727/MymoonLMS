<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Classes\CinetPay;
use App\Classes\Vimeo;
use App\Models\AdsPlan;
use App\Models\AdsRequest;
use App\Models\Article;
use App\Models\ArticleRate;
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
use App\Models\ContentRate;
use App\Models\ContentSupport;
use App\Models\DiscountContent;
use App\Models\Schedule;
use App\Models\Follower;
use App\Models\MeetingDate;
use App\Models\MeetingLink;
use App\Models\Notification;
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
use App\Models\UserRate;
use App\Models\Country;
use App\User;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Razorpay\Api\Api;
use Unicodeveloper\Paystack\Facades\Paystack;

class BookController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user) {
            return redirect('/login');
        }
        $content_type = $request->type;
        $content_id = intval($request->id);
        $duration = $data['duration'] = $request->dur;
        $error = array();

        if ($content_type == 'onetoone') {
            $profile = User::where('id', $request->id)->get();

            // added by Bozo
            $user = auth()->user();
            $student = Usermeta::where('user_id', $user->id)->pluck('value', 'option')->all();

            if(@$student['meta_dob']) {

                if($profile->count() == 1){
                    $content = Content::where('type', '1')->where('user_id',$profile[0]->id)->get();
                    if($content->count() == 1){
                        $teacher_hourly_rate=get_user_meta($profile[0]->id,'meta_hourly_rate',0);
                        $book_count = Schedule::where('course_id', $content[0]->id)
                            ->where('part_id', '0')
                            ->where('booking_user_id', $user->id)
                            ->where('status', 'temp_booked')
                            ->get()->count();

                        if($teacher_hourly_rate > 0){
                            $data['content'] = $content[0];
                            $data['profile'] = $profile[0];
                            $data['userMetas'] = get_all_user_meta($profile[0]->id);
                            $data['user'] = Auth::user();
                            $data['type'] = 'onetoone';
                            $data['price'] = $book_count*($teacher_hourly_rate/2);
                            $data['buy'] = 0;

                        }else{
                            $error[] = 'Teachers hourly rate not defined';
                        }

                    }else{
                        $error[] = 'Teachers one to one plan not found';
                    }

                }else{
                    $error[] = 'Teacher not found';
                }
                if (count($error) > 0) {
                    $data['error'] = $error;
                    return view(getTemplate() . '.user.error', $data);
                } else {
                    return view(getTemplate() . '.user.content.bookOneToOne', $data);
                }

            }else{
                $error[] = "Please set your birthday to Personal Details page. That's required property when you book the session.";

                $data['error'] = $error;
                return view(getTemplate() . '.user.studentageerror', $data);
            }
            // ended

        }else if ($content_type == 'plan') {
            $plan = ContentPart::where('id', $content_id)->get();
            if ($plan->count() == 1) {
                $content = Content::where('id', $plan[0]->content_id)->first();
                $profile = User::where('id', $content->user_id)->first();

                $str = '';
                $events = Schedule::where('part_id', $plan[0]->id)->get();
                $done_days = array();

                $schedule = array();
                foreach ($events as $event) {

                    $only_date=substr($event->start, 0, 10);
                    $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
                    $actual_day=date('D',strtotime($formatted_date));
                    $start_time = strtotime(substr($event->start, -9,-4));
                    $end_time = strtotime(substr($event->end, -9,-4));
                    if (!in_array($actual_day, $done_days)) {
                        $schedule[] = array('day' => $actual_day, 'start_time' => $start_time, 'end_time' => $end_time);
                        $done_days[] = $actual_day;
                    }
                }

                $buy = Sell::where('buyer_id', $user->id)->where('content_part_id', $content_id)->count();
                $data['buy'] = $buy;

                $data['content'] = $content;
                $data['profile'] = $profile;
                $data['userMetas'] = get_all_user_meta($profile->id);
                $data['content_part'] = $plan[0];
                $data['schedule'] = $schedule;
                $data['user'] = Auth::user();
                $data['type'] = 'plan';

                if ($duration == 30) {
                    $data['price'] = $plan[0]->price;
                } else if ($duration == 60) {
                    $data['price'] = $plan[0]->price;
                }


            } else {
                $error[] = 'Plan not found';
            }
        } else if ($content_type == 'video') {
            $content = Content::where('id', $content_id)->get();

            if ($content->count() == 1) {
                $videos = ContentPart::where('content_id', $content[0]->id)->get();
                $profile = User::where('id', $content[0]->user_id)->first();

                $buy = Sell::where('buyer_id', $user->id)->where('content_id', $content_id)->count();
                $data['buy'] = $buy;

                $data['content'] = $content[0];
                $data['profile'] = $profile;
                $data['userMetas'] = get_all_user_meta($profile->id);
                $data['videos'] = $videos;
                $data['user'] = Auth::user();
                $data['type'] = 'video';
                $data['price'] = $content[0]->price;
            } else {
                $error[] = 'Course not found';
            }

        } else if ($content_type == 'group') {
            $content = Content::where('id', $content_id)->get();

            if ($content->count() == 1) {
                $profile = User::where('id', $content[0]->user_id)->first();

                $str = '';
                $events = Schedule::where('course_id', $content[0]->id)->get();
                $done_days = array();

                $schedule = array();
                foreach ($events as $event) {
                    $only_date=substr($event->start, 0, 10);
                    $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
                    $actual_day=date('D',strtotime($formatted_date));
                    $start_time = strtotime(substr($event->start, -9,-4));
                    $end_time = strtotime(substr($event->end, -9,-4));
                    if (!in_array($actual_day, $done_days)) {
                        $schedule[] = array('day' => $actual_day, 'start_time' => $start_time, 'end_time' => $end_time);
                        $done_days[] = $actual_day;
                    }
                }
                $data['schedule'] = $schedule;

                $buy = Sell::where('buyer_id', $user->id)->where('content_id', $content_id)->count();
                $data['buy'] = $buy;

                $data['content'] = $content[0];
                $data['profile'] = $profile;
                $data['userMetas'] = get_all_user_meta($profile->id);
                $data['user'] = Auth::user();
                $data['type'] = 'group';
                $data['price'] = $content[0]->price;
            } else {
                $error[] = 'Course not found';
            }
        } else {
            $error[] = 'Content type is incorrect';
        }

        if (count($error) > 0) {
            $data['error'] = $error;
            return view(getTemplate() . '.user.error', $data);
        } else {
            return view(getTemplate() . '.user.content.bookDetails', $data);
        }

    }





    public function creditPay(Request $request)
    {

        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user) {
            return redirect('/login');
        }

        $content = Content::where('mode', 'publish')->find($request->content_id);
        if (!$content)
            abort(404);

        if($user->type =='Teacher'){
            $message='You cant buy any course.Please create a student profile to buy a course';
            return $message;
        }


        $message = '';
        $pay_type='single';
        if ($request->type == 'plan') {
            $content_part = ContentPart::where('mode', 'publish')->find($request->plan_id);
            $book_count = Schedule::where('course_id', $request->content_id)
                ->where('part_id', $request->plan_id)
                ->where('booking_user_id', $user->id)
                ->where('status', 'temp_booked')
                ->get()->count();


            if ($book_count < intval($content_part->class_number)) {
                $message = 'Please select ' . $content_part->class_number . ' classes. You have selected ' . $book_count . ' classes';
                return $message;
            }


            $content_part_id = $content_part->id;
            $Amount = $content_part->price;
            $pay_type='recurring';

        }else if($request->type == 'group'){
            if($content->seats_available == null || $content->seats_available > 0){
                $content_part_id = 0;
                $Amount = $content->price;
            }else{
                $message = 'No seats are available for this course';
                return $message;
            }
        } else {
            $content_part_id = 0;
            $Amount = $content->price;
        }


        $to_usd = currency($request->don, $request->cur, 'USD');
        $usd_don = str_replace('$', '', $to_usd);
        $donation = floatval($usd_don);
        $coupon = 0;

        $seller = User::find($content->user_id);

        //$site_income = get_option('site_income');
        $user_credit = getUserbalance($user->id);

        if ($Amount !== null && $Amount !=='' && intval($Amount) >=0 ) {
            $Amount_pay = $Amount + $donation - $coupon;
            if ($user_credit - $Amount_pay < 0) {
                $message = 'noCredit';

            } else {

                $mymoon_income = ($Amount_pay * 25) / 100;
                $teacher_income = ($Amount_pay * 75) / 100;
                $service_charge = ($teacher_income * 1.5) / 100;
                $teacher_income = $teacher_income - $service_charge;

                $transaction = Transaction::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $content->user_id,
                    'content_id' => $content->id,
                    'content_part_id' => $content_part_id,
                    'price' => $Amount_pay,
                    'price_content' => $Amount,
                    'donation' => $donation,
                    'coupon' => $coupon,
                    'seller_income' => $teacher_income,
                    'mymoon_income' => $mymoon_income,
                    'service_charge' => $service_charge,
                    'mode' => 'deliver',
                    'created_at' => time(),
                    'bank' => 'credit',
                    'authority' => '000',
                    'type' => 'book'
                ]);
                Sell::insert([
                    'seller_id' => $content->user_id,
                    'buyer_id' => $user->id,
                    'content_id' => $content->id,
                    'content_part_id' => $content_part_id,
                    'type' => $pay_type,
                    'created_at' => time(),
                    'mode' => 'pay',
                    'transaction_id' => $transaction->id
                ]);


                Balance::create([
                    'title' => 'Course_Purchased',
                    'description' => 'cp_'.$transaction->id . '_' . $content->id . '_' . $content_part_id.'_'.time(),
                    'type' => 'buy',
                    'price' => $Amount_pay,
                    'status' => 'success',
                    'user_id' => $user->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'Course_Sold:',
                    'description' => 'cs_'.$transaction->id . '_' . $content->id . '_' . $content_part_id.'_'.time(),
                    'type' => 'sell',
                    'price' => $teacher_income,
                    'status' => 'success',
                    'user_id' => $seller->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'MyMoon_Course_Sold:' ,
                    'description' =>'mcs_'.$transaction->id . '_' . $content->id . '_' . $content_part_id.'_'.time(),
                    'type' => 'mymoon_charge',
                    'price' => $mymoon_income,
                    'status' => 'success',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                Balance::create([
                    'title' => 'MyMoon_Service_Charge:',
                    'description' => 'msc_'.$transaction->id . '_' . $content->id . '_' . $content_part_id.'_'.time(),
                    'type' => 'service_charge',
                    'price' => $service_charge,
                    'status' => 'success',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                if ($request->type == 'plan') {

                    $sc=Schedule::where('course_id', $request->content_id)
                        ->where('part_id', $request->plan_id)
                        ->where('booking_user_id', $user->id)
                        ->where('status', 'temp_booked')
                        ->get();

                    foreach($sc as $cls) {

                        $class_entry = ClassModel::create([
                            'schedule_id'=>$cls->id,
                            'course_id'=>$cls->course_id,
                            'part_id'=>$cls->part_id,
                            'course_type'=>$cls->course_type,
                            'user_id'=>$cls->user_id,
                            'booking_user_id'=>$cls->booking_user_id,
                            'title'=>$cls->title,
                            'start'=>$cls->start,
                            'end'=>$cls->end,
                            'status'=>'booked',
                            'timeval'=>$cls->timeval,
                        ]);
                    }

                    $book_update = Schedule::where('course_id', $request->content_id)
                        ->where('part_id', $request->plan_id)
                        ->where('booking_user_id', $user->id)
                        ->where('status', 'temp_booked')
                        ->update(['status' => 'booked']);
                }

                if ($request->type == 'group') {
                    $sc=Schedule::where('course_id', $request->content_id)->get();
                    foreach($sc as $cls) {
                        $check_group_course=ClassModel::where('course_id',$cls->course_id)
                            ->where('user_id',$cls->user_id)
                            ->where('start',$cls->start)
                            ->where('end',$cls->end)
                            ->where('course_type',$cls->course_type)
                            ->get();

                        if($check_group_course->count() > 0){
                            $existing_students=explode(',',$check_group_course[0]->booking_user_id);
                            $existing_students[]=$user->id;

                            $update_group_class=ClassModel::where('id',$check_group_course[0]->id)->update([
                                'booking_user_id'=>implode(',',$existing_students),
                            ]);

                        }else{
                            $class_entry = ClassModel::create([
                                'schedule_id'=>$cls->id,
                                'course_id'=>$cls->course_id,
                                'part_id'=>$cls->part_id,
                                'course_type'=>$cls->course_type,
                                'user_id'=>$cls->user_id,
                                'booking_user_id'=>$user->id,
                                'title'=>$cls->title,
                                'start'=>$cls->start,
                                'end'=>$cls->end,
                                'status'=>'booked',
                                'timeval'=>$cls->timeval,
                            ]);
                        }

                    }
                    $book_update = Schedule::where('course_id', $request->content_id)->update(['status' => 'booked']);
                    $course_update = Content::where('id', $request->content_id)->update(['seats_available' => intval($content->seats_available)-1]);

                }

                ## Notification Center
                $product = Content::find($transaction->content_id);
                sendMail([
                    'recipient' => [$seller->email],
                    'template' => 18,
                    'subject' => 'You have a new booking!',
                    'title' => 'You have a new booking! ',
                    'content' => '',
                ]);
                sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $user->id);
                $message = 'ok';
            }
        }

        return $message;
    }

    public function creditPaySingle(Request $request)
    {


        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user) {
            return redirect('/login');
        }
        $message = '';
        $content = Content::where('mode', 'publish')->find($request->content_id);
        if (!$content){
            $message = 'The course is not published';
        }

        if($user->type =='Teacher'){
            $message='You cant buy any course.Please create a student profile to buy a course';
            return $message;
        }

        $book_count = Schedule::where('course_id', $content->id)
            ->where('part_id', '0')
            ->where('booking_user_id', $user->id)
            ->where('status', 'temp_booked')
            ->get()->count();

        $hourly_rate=get_user_meta($content->user_id,'meta_hourly_rate',0);
        $content_part_id = 0;
        if(floatval($hourly_rate) > 0){
            $Amount = $book_count*($hourly_rate/2);
        }else{
            $Amount = 0;
        }



        $to_usd = currency($request->don, $request->cur, 'USD');
        $usd_don = str_replace('$', '', $to_usd);
        $donation = floatval($usd_don);
        $coupon = 0;

        $seller = User::find($content->user_id);

        //$site_income = get_option('site_income');
        $user_credit = getUserbalance($user->id);

        if (!empty($Amount)) {
            $Amount_pay = $Amount + $donation - $coupon;
            if ($user_credit - $Amount_pay < 0) {
                $message = 'noCredit';
            } else {

                $mymoon_income = ($Amount_pay * 25) / 100;
                $teacher_income = ($Amount_pay * 75) / 100;
                $service_charge = ($teacher_income * 1.5) / 100;
                $teacher_income = $teacher_income - $service_charge;

                $transaction = Transaction::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $content->user_id,
                    'content_id' => $content->id,
                    'content_part_id' => $content_part_id,
                    'price' => $Amount_pay,
                    'price_content' => $Amount,
                    'donation' => $donation,
                    'coupon' => $coupon,
                    'seller_income' => $teacher_income,
                    'mymoon_income' => $mymoon_income,
                    'service_charge' => $service_charge,
                    'mode' => 'deliver',
                    'created_at' => time(),
                    'bank' => 'credit',
                    'authority' => '000',
                    'type' => 'book'
                ]);
                Sell::insert([
                    'seller_id' => $content->user_id,
                    'buyer_id' => $user->id,
                    'content_id' => $content->id,
                    'content_part_id' => $content_part_id,
                    'type' => 'book',
                    'created_at' => time(),
                    'mode' => 'pay',
                    'transaction_id' => $transaction->id
                ]);


                Balance::create([
                    'title' => 'Course_Purchased:' . $transaction->id . '_' . $content->id . '_' . $content_part_id,
                    'description' => trans('admin.item_purchased_desc'),
                    'type' => 'buy',
                    'price' => $Amount_pay,
                    'status' => 'success',
                    'user_id' => $user->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'Course_Sold:' . $transaction->id . '_' . $content->id . '_' . $content_part_id,
                    'description' => trans('admin.item_sold_desc'),
                    'type' => 'sell',
                    'price' => $teacher_income,
                    'status' => 'success',
                    'user_id' => $seller->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'MyMoon_Course_Sold:' . $transaction->id . '_' . $content->id . '_' . $content_part_id,
                    'description' => trans('admin.item_profit_desc'),
                    'type' => 'mymoon_charge',
                    'price' => $mymoon_income,
                    'status' => 'success',
                    'user_id' => 1,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                Balance::create([
                    'title' => 'MyMoon_Service_Charge:' . $transaction->id . '_' . $content->id . '_' . $content_part_id,
                    'description' => 'Service Charge',
                    'type' => 'service_charge',
                    'price' => $service_charge,
                    'status' => 'success',
                    'user_id' => 1,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                $book_count = Schedule::where('course_id', $content->id)
                    ->where('part_id', '0')
                    ->where('booking_user_id', $user->id)
                    ->where('status', 'temp_booked')
                    ->update(['status' => 'booked']);

                ## Notification Center
                $product = Content::find($transaction->content_id);
                sendMail([
                    'recipient' => [$seller->email],
                    'template' => 18,
                    'subject' => 'You have a new booking!',
                    'title' => 'You have a new booking! ',
                    'content' => '',
                ]);

                $sc=Schedule::where('course_id', $content->id)->where('status','booked')->where('booking_user_id',$user->id)->get();
                foreach($sc as $cls) {
                    $class_entry = ClassModel::create([
                        'schedule_id'=>$cls->id,
                        'course_id'=>$cls->course_id,
                        'part_id'=>$cls->part_id,
                        'course_type'=>$cls->course_type,
                        'user_id'=>$cls->user_id,
                        'booking_user_id'=>$user->id,
                        'title'=>$cls->title,
                        'start'=>$cls->start,
                        'end'=>$cls->end,
                        'status'=>'booked',
                        'timeval'=>$cls->timeval,
                    ]);
                }


                sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), '', $user->id);
                $message = 'ok';
            }
        }

        return $message;
    }

    public function renewPlan(Request $request){

        $user = (auth()->check()) ? auth()->user() : false;
        if (!$user) {
            return redirect('/login');
        }
        $message = '';

        if($user->type =='Teacher'){
            $message='You cant buy any course.Please create a student profile to buy a course';
            return back()->with('msg', trans($message));
        }

        $sell_id=$request->input('id');
        $sell=Sell::where('id',$sell_id)->get();
        if($sell->count() == 1){
            $sell=$sell[0];
            $plan = ContentPart::find($sell->content_part_id);
            if (!$plan){
                $message = 'The Plan is not available';
                return back()->with('msg', trans($message));
            }
        }else{
            $message = 'ID not valid';
            return back()->with('msg', trans($message));
        }
            $content_part_id = $sell->content_part_id;
            $Amount = $plan->price;
            $pay_type='recurring';

        $donation = 0;
        $coupon = 0;

        $seller = User::find($sell->seller_id);

        //$site_income = get_option('site_income');
        $user_credit = getUserbalance($user->id);

        if ($Amount !== null && $Amount !=='' && intval($Amount) >=0 ) {
            $Amount_pay = $Amount + $donation - $coupon;
            if ($user_credit - $Amount_pay < 0) {
                $message = 'noCredit';
                return back()->with('msg', trans($message));
            } else {

                $mymoon_income = ($Amount_pay * 25) / 100;
                $teacher_income = ($Amount_pay * 75) / 100;
                $service_charge = ($teacher_income * 1.5) / 100;
                $teacher_income = $teacher_income - $service_charge;

                $transaction = Transaction::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $sell->seller_id,
                    'content_id' => $sell->content_id,
                    'content_part_id' => $content_part_id,
                    'price' => $Amount_pay,
                    'price_content' => $Amount,
                    'donation' => $donation,
                    'coupon' => $coupon,
                    'seller_income' => $teacher_income,
                    'mymoon_income' => $mymoon_income,
                    'service_charge' => $service_charge,
                    'mode' => 'deliver',
                    'created_at' => time(),
                    'bank' => 'credit',
                    'authority' => '000',
                    'type' => 'book'
                ]);

                $update_sell=Sell::where('id',$sell->id)->update(['status'=>'ongoing']);

                Balance::create([
                    'title' => 'Course_Purchased',
                    'description' => 'cp_'.$transaction->id . '_' . $sell->content_id . '_' . $content_part_id.'_'.time(),
                    'type' => 'buy',
                    'price' => $Amount_pay,
                    'status' => 'success',
                    'user_id' => $user->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'Course_Sold:',
                    'description' => 'cs_'.$transaction->id . '_' . $sell->content_id . '_' . $content_part_id.'_'.time(),
                    'type' => 'sell',
                    'price' => $teacher_income,
                    'status' => 'success',
                    'user_id' => $seller->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => 'MyMoon_Course_Sold:' ,
                    'description' =>'mcs_'.$transaction->id . '_' . $sell->content_id . '_' . $content_part_id.'_'.time(),
                    'type' => 'mymoon_charge',
                    'price' => $mymoon_income,
                    'status' => 'success',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                Balance::create([
                    'title' => 'MyMoon_Service_Charge:',
                    'description' => 'msc_'.$transaction->id . '_' . $sell->content_id . '_' . $content_part_id.'_'.time(),
                    'type' => 'service_charge',
                    'price' => $service_charge,
                    'status' => 'success',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                $message = 'Plan renewal successful';
                return back()->with('msg', trans($message));
            }
        }else{
            $message='Something went wrong.';
            return back()->with('msg', trans($message));
        }
    }



    function updateDonationPrice(Request $request)
    {

        $to_usd = currency($request->don, $request->cur, 'USD');
        $usd_don = str_replace('$', '', $to_usd);
        $total = floatval($request->main) + floatval($usd_don);
        return currency($total, 'USD', $request->cur);
    }

    function updateTotalPrice(Request $request)
    {

        $hourly_rate=get_user_meta($request->teacher,'meta_hourly_rate',0);
        $total=intval($request->slot)*($hourly_rate/2);
        $total = floatval($total);
        return currency($total, 'USD', $request->cur);
    }
}
