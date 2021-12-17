<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Usermeta;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest','notification']);
    }

    public function showRegistrationForm()
    {

        return redirect()->back()->with('msg',trans('Thanks for Registration.Please check your email inbox or spam folder for activation email.'));

        return view(getTemplate().'.auth.login');
    }

	public function registerStudent()
    {
        return view(getTemplate().'.auth.registerStudent');
    }

	public function registerTeacher()
    {
        $country = Country::orderBy('country_name', 'ASC')->get();
        return view(getTemplate().'.auth.registerTeacher',compact('country'));
    }

    public function resetPassword()
    {
        return view(getTemplate().'.auth.reset');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {


        if($request->type =='student'){
            $this->studentValidator($request->all())->validate();
        }else{
            $this->validator($request->all())->validate();
        }


        $userTableData=[
            'username'=>$request->username,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'mode' => get_option('user_register_mode', 'email_unverified'),
            'token' => Str::random(25)
            ];

        $user_id=User::insertGetId($userTableData);
        $user=User::where('id',$user_id)->first();

        //$user = $this->create($request->all());

        if($request->type == 'teacher') {
            $user_update = User::where('id', $user_id)->update(['type' => 'Teacher']);

            $userMetaData=[
                'meta_dob'=>$request->dob,
                'meta_country'=>$request->nationality,
                'meta_living_in'=>$request->residence,
                'meta_gender'=>$request->gender,
                'meta_mobile'=>$request->mobile,
                'meta_fb_profile'=>$request->profile,
            ];

            $path = public_path('bin/'.$user->username);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $passportPhoto = $request->file('passportPhoto');
            if($passportPhoto !== null){
                $name = uniqid() . '_' . trim($passportPhoto->getClientOriginalName());
                $passportPhoto->move($path, $name);
                $userMetaData['meta_avatar']= 'bin/'.$user->username.'/'.$name;
            }


            $file = $request->file('resume');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $file->move($path, $name);
            $userMetaData['meta_resume_file'] = 'bin/'.$user->username.'/'.$name;

            $file = $request->file('certificates');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $file->move($path, $name);
            $userMetaData['meta_certificates_file'] = 'bin/'.$user->username.'/'.$name;

            Usermeta::updateOrNew($user->id, $userMetaData);
        }else{
            $user_update = User::where('id', $user_id)->update(['type' => 'Student']);
        }


        //$this->guard()->login($user);

        ## Send Suitable Email For New User ##
        $user_register_mode = get_option('user_register_mode');

        if($user_register_mode == 'email_unverified'){
            sendMail([
                'template' => get_option('user_register_active_email'),
                'recipient' => [$user->email]
                ]);
            return redirect()->back()->with('msg',trans('Thanks for Registration.Please check your email inbox or spam folder for activation email.'));
        }else {
            sendMail([
                'template'=>get_option('user_register_welcome_email'),
                'recipient'=>[$user->email]
            ]);
            return redirect()->back()->with('msg',trans('main.active_account_alert'));
        }
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255','unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'dob' => ['required'],
            'nationality' => ['required'],
            'gender' => ['required'],
            'mobile' => ['required'],
            'residence' => ['required'],
            'certificates' => ['required'],
            'passportPhoto' => ['required'],
            'resume' => ['required'],
        ]);
    }
    protected function studentValidator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255','unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'created_at' => time(),
            'admin' => false,
            'mode' => get_option('user_register_mode', 'active'),
            'category_id' => get_option('user_default_category', 0),
            'token' => Str::random(25)
        ]);
    }
}
