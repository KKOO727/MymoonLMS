<?php

namespace App\Http\Middleware;
use App\Models\ClassModel;
use App\Models\Messages;
use App\Models\Notification;
use App\Models\Sell;
use App\Models\Social;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class NotificationMiddleware
{
    public function handle($request, Closure $next)
    {


        $locale = Cookie::get('lang');
        app()->setLocale($locale);

        $currency = getCurrency();
        currency()->setUserCurrency($currency);

        //app()->setLocale(get_option('site_language', 'en'));

        if (session()->has('impersonated')) {
            Auth::onceUsingId(session()->get('impersonated'));
        }


        if (auth()->check()) {
            $user = auth()->user();
            view()->share('user', $user);


            global $alert;
            $alert = [];
            $alert['notification'] = 0;
            $alert['message'] = 0;
            $alert['tickets'] = 0;
            $alert['homework'] = [];
            $events = array();

            $active_courses = Sell::where('buyer_id', $user->id)->where('status', 'ongoing')->get();

            foreach($active_courses as $sell){
                $temp_events = ClassModel::where('course_id', $sell->content_id)->where('booking_user_id', $user->id)->get();
                $events[$sell->content_id] = $temp_events;
            }
            $alert['homework'] = $events;

            $notifications = Notification::where('user_id', 0)
                ->where('recipent_list', $user->id)
                // ->orWhere('recipent_type', 'all')
                // ->where('mode', 'publish')
                ->orderBy('id', 'DESC')->get();

            $notificationLists = Notification::where('recipent_list', $user->id)->orderBy('id', 'DESC')->get();

            $alert['notification'] = $notifications->count();


            // $notification = Notification::where('recipent_list', $user->id)
            //     ->with(['status' => function ($query) use ($user) {
            //         $query->where('user_id', $user->id);
            //     }])->get();

            // foreach ($notification as $noti) {
            //     if ($noti->status->count() == 0)
            //         $alert['notification']++;
            // }

            $messages = Messages::with('threads')->orderBy('id', 'DESC')->where('user_id', $user->id)->orWhere('message_to', $user->id)->get();

            foreach ($messages as $msg) {
                foreach ($msg->threads as $thread) {

                    if ($thread->view == 0 && $thread->user_id != $user->id) {
                        $alert['message']++;
                    }
                }
            }
            view()->share('alert', $alert);

            if ($request->getRequestUri() != '/user/dailyFeedback') {
                if ($user->type == 'Student') {
                    //$today=strtotime(date('Y-m-d'));
                    //$check_p=ClassModel::where('timeval','<',$today)->where('completed','0')->where('booking_user_id',$user->id)->get();
                    //if($check_p->count() > 0){
                    //    return redirect('/user/dailyFeedback');
                    //}
                }
            }

        }


        #### Get Footer Socials ####
        ############################
        $socials = Social::orderBy('sort')->get();
        view()->share('socials', $socials);
        view()->share('currency', $currency);


        return $next($request);
    }
}
