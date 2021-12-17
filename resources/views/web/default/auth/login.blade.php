@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : 'Website Title' }}
    {{ trans('main.user_login') }}
@endsection
@section('page')
<?php $base_url= baseURL(); ?>

<div class="login-container login">
    <div class="login-header">
        <div class="row">
            <div class="col-md-3"><h2>Log in</h2></div>
            <div class="col-md-9">
                <div class="login-options">
                    <a href="{{url('/registerTeacher')}}">Sign up as a Teacher </a> or <a href="{{url('/registerStudent')}}"> Sign up as a Student</a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="login-body">
        <form class="form" action="{{ $base_url}}/login" method="post" id="loginForm" style="text-align: left;direction: ltr" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-9">
                    <input type="text" name="username" class="form-control validate" autocomplete="new-password" placeholder="Type your username/email address" required>
                    <u></u>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-9">
                    <input type="password" name="password" class="form-control validate" placeholder="Fill out this form" autocomplete="new-password" required>
                    <u></u>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9 remember-me-checkbox">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" value="1" name="remember">
                    <label class="form-check-label" for="exampleCheck1">{{ trans('main.remember') }}</label>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-custom pull-left btn-register-user-r"><span>{{ trans('main.sign_in') }}</span></button>

            </div>
            <div class="h-20"></div>
            <div class="form-group text-center justify-content-center">
                <a href="{{url('/resetPassword')}}" style="color: #242424 !important;">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
