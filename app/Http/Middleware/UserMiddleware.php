<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Login;
use App\Models\Schedule;
use App\Models\Sell;
use App\Models\Usermeta;
use Closure;
use App\User;
use Illuminate\Support\Facades\Cookie;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {

        $locale=Cookie::get('lang');
        app()->setLocale($locale);
        //app()->setLocale(get_option('site_language','en'));

        if(!auth()->check()){
            return redirect('/login');
        } else {
            $user = auth()->user();

            $login = Login::where('user_id', $user->id)->orderBy('id','DESC')->first();
            if(get_option('duplicate_login',0) == 1) {
                if (isset($login)) {
                    if ($login->created_at_sh + 300 > time()) {
                        return back()->with('msg', trans('main.access_denied_duplicate_login'));
                    }
                }
            }

            $New = Login::create([
                'user_id'       => $user->id,
                'created_at_sh' => time(),
                'updated_at_sh' => time()
            ]);

            Event::create([
                'user_id'   => $user->id,
                'type'      => 'Cookie',
                'ip' => $request->ip()
            ]);

            $usermeta = Usermeta::where('user_id',$user->id)->get();
            $userMeta = arrayToList($usermeta,'option','value');
            $_SESSION["kc_disable"] = false;
            $_SESSION["kc_uploadedir"] = $user->username;
            $_SESSION["kc_allow"] = true;

            view()->share('user',$user);
            view()->share('userMeta',$userMeta);

            return $next($request);
            //$request->session()->put('user',serialize($user->toArray()));
        }
    }
}
