@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : 'Website Title' }}
    {{ trans('main.user_login') }}
@endsection
@section('page')
    <?php $base_url= baseURL(); ?>



    <div class="login-container student-register">
        <div class="login-header">
            <div class="row">
                <div class="col-md-6"><h2>Student Register</h2></div>
                <div class="col-md-6">
                    <div class="login-options">
                        <a href="{{url('/login')}}">Login</a> or <a href="{{url('/registerTeacher')}}">Sign up as a Teacher</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="form" method="post" action="/registerUser" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">{{ trans('main.username') }}</label>
                    <div class="col-sm-9">
                        <input type="text" valid-title="Fill out this form." name="username" class="form-control validate" required>
                        <u></u>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">{{ trans('main.password') }}</label>
                    <div class="col-sm-9">
                        <input type="password" id="r-password" valid-title="Fill out this form." name="password" class="form-control validate" required>
                        <u></u>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="r-re-password" class="col-sm-3 col-form-label">{{ trans('main.retype_password') }}</label>
                    <div class="col-sm-9">
                        <input type="password" id="r-re-password" name="password_confirmation" valid-title="Fill out this form." class="form-control validate" required>
                        <u></u>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="realname" class="col-sm-3 col-form-label">{{ trans('main.realname') }}</label>
                    <div class="col-sm-9">
                        <input  type="name" name="name" class="form-control validate" valid-title="Enter your real name" required>
                        <u></u>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="realname" class="col-sm-3 col-form-label">{{ trans('main.email') }}</label>
                    <div class="col-sm-9">
                        <input  type="email" name="email" class="form-control validate" valid-title="Enter your email address" required>
                        <u></u>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="realname" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <input type="checkbox" class="input-r" name="terms" style="display: block;position: relative;top: 18px;width: auto;height: auto" valid-title="If you want to continue please accept terms and rules" required>
                        <label class="label-r" style="margin-left: 20px;">{{ trans('main.i_accept') }} <a href="/page/pages_terms" target="_blank">{{ trans('main.term_rules') }}</a></label>
                        <u></u>
                    </div>
                </div>

                <div class="form-group row">
                    <input type="hidden" name="type" value="student">
                    <button class="btn btn-custom pull-left btn-register-user-r"><span>Register</span></button>
                    <br>
                </div>
                <div class="form-group row text-center justify-content-center">
                    <a href="{{url('/resetPassword')}}" style="color: #242424 !important;">Forget Password?</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
