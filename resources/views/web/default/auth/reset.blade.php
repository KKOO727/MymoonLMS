@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : 'Website Title' }}
    {{ trans('main.user_login') }}
@endsection
@section('page')
<?php $base_url= baseURL(); ?>


<div class="login-container forgot-password">
    <div class="login-header">
        <div class="row">
            <div class="col-md-6"><h2>Reset Password</h2></div>
            <div class="col-md-6">
                <div class="login-options">
                    <a href="{{url('/login')}}">Login</a> or <a href="{{url('/registerTeacher')}}">Sign up as a Teacher</a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="login-body">
        <form class="form" action="/user/reset" method="post">
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" value="" name="email">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-custom pull-left btn-register-user-r" type="submit"><span>Reset Password</span></button>
                <br>
            </div>
<div class="h-20"></div>
        </form>
    </div>
</div>
@endsection
@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
