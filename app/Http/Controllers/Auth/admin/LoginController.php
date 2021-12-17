<?php

namespace App\Http\Controllers\Auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form. Overrided
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min:4',
        ];

        if ($this->username() == 'email') {
            $rules['username'] = 'required|email';
        }

        $this->validate($request, $rules);

        if ($this->attemptLogin($request)) {
            return $this->afterLogged($request);
        } else {
            return redirect('/admin/login')->withErrors([trans('main.incorrect_login')]);
        }
    }

    public function username()
    {
        $email_regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

        if (empty($this->username)) {
            $this->username = 'username';
            if (preg_match($email_regex, request('username', null))) {
                $this->username = 'email';
            }
        }
        return $this->username;
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = [
            $this->username() => $request->get('username'),
            'password' => $request->get('password')
        ];
        $remember = false;
        if (!empty($request->get('remember')) and $request->get('remember') == true) {
            $remember = true;
        }
        return $this->guard()->attempt($credentials, $remember);
    }

    public function afterLogged(Request $request)
    {
        $user = auth()->user();
        $userBlock = userMeta($user->id, 'blockDate');

        if (!empty($userBlock) and $user->mode !== 'active') {
            if ($userBlock < time()) {
                $user->mode = 'active';
            } else {
                auth()->logout();
                $blockDate = date('d F Y', $userBlock);
                return redirect()->back()->withErrors([trans('main.access_denied') . $blockDate]);
            }
        }

        //$user->last_view = time();
        //$user->updated_at = time();
        $user->save();

        Event::create([
            'user_id' => $user->id,
            'type' => 'Login Page',
            'ip' => $request->ip()
        ]);

        if ($user->type == 'admin') {
            return redirect('/admin');
        } else {
            if ($request->session()->has('redirect')) {
                return redirect($request->session()->has('redirect'));
            } else {
                return redirect('/user/dashboard');
            }
        }

    }
}
