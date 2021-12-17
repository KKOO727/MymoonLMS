<?php

namespace App\Http\Middleware;

use App\Models\Article;
use App\Models\Content;
use App\Models\Notification;
use App\Models\Tickets;
use App\User;
use App\Models\Usermeta;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use App\Models\ChannelRequest;
use Illuminate\Support\Facades\Route;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {


        $locale=Cookie::get('lang');
        app()->setLocale($locale);

        if (auth()->check()) {
            $admin = auth()->user();
            if ($admin->isAdmin()) {

                $_SESSION["kc_disable"] = false;
                $_SESSION["kc_uploadedir"] = $admin->username;
                $_SESSION["kc_allow"] = true;

                \Session::forget('impersonated');

                $Meta = Usermeta::where('option', 'capatibility')->where('user_id', $admin->id)->first();

                if (isset($Meta->value)) {
                    $capatibilty = unserialize($Meta->value);
                } else {
                    $capatibilty = 'all';
                }

                if (!empty($capatibilty) and $capatibilty != 'all' and !in_array(Request::segment(2), $capatibilty) and Request::segment(2) != 'profile' and Request::segment(2) != 'video' and Request::segment(2) != 'about')
                    return abort(404);
                global $Access;
                $Access = $capatibilty;
                view()->share('capatibility', $capatibilty);

                ## Notify , Ticket Section ##
                #############################
                $alert = [];
                //$alert['notification'] = Notification::with('user')->where('created_at', '>', $admin->last_view)->get();
                //$alert['ticket'] = Tickets::with('user')->where('created_at', '>', $admin->last_view)->get();
                //$alert['withdraw'] = User::where('seller_income', '>=', get_option('site_withdraw_price', 0))->count();
                //$alert['channel_request'] = ChannelRequest::where('mode', 'draft')->count();
                //$alert['content_draft'] = Content::where('mode', 'draft')->count();
                //$alert['content_waiting'] = Content::where('mode', 'waiting')->count();
                //$alert['article_request'] = Article::where('mode', 'request')->count();
                //$alert['seller_apply'] = User::where('mode', 'active')->where('type','!=','admin')->count();
                view()->share('alert', $alert);

                ## Segment
                view()->share('menu', $request->segment(2));
                view()->share('submenu', $request->segment(3));
                view()->share('url', url()->current());
                view()->share('Admin', $admin);

                return $next($request);
            }
        }

        return redirect('/admin/login');
    }
}
