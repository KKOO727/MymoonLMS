@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : 'Website Title' }}
    {{ trans('main.user_login') }}
@endsection
@section('page')


    <div class="login-s" style="background: url('{!! baseURL() !!}{{ get_option('login_page_background') }}');min-height: 750px;">
        <div class="h-25"></div>
        <div class="h-25"></div>
        <div class="container text-center">
            <div class="formBox level-login" dir="ltr">
               
               
                
                <div class="box registerBoxs" style="height: 650px">
                    <span class="reg_bg"></span>
                    <form class="form" method="post" action="{!! baseURL() !!}/registerUser" style="text-align: left">
                        {{ csrf_field() }}
                        <div class="f_row">
                            <label>{{ trans('main.username') }}</label>
                            <input type="text" name="username" valid-title="Fill out this form." class="input-field validate" required>
                            <u></u>
                        </div>
                        <div class="f_row">
                            <label>{{ trans('main.password') }}</label>
                            <input type="password" id="r-password" valid-title="Fill out this form." name="password" class="input-field validate" required>
                            <u></u>
                        </div>
                        <div class="f_row">
                            <label>{{ trans('main.retype_password') }}</label>
                            <input type="password" id="r-re-password" name="password_confirmation" valid-title="Fill out this form." class="input-field validate" required>
                            <u></u>
                        </div>
                        <div class="f_row">
                            <label>{{ trans('main.realname') }}</label>
                            <input type="name" name="name" class="input-field validate" valid-title="Enter your real name" required>
                            <u></u>
                        </div>
                        <div class="f_row last" style="margin-bottom: 20px;">
                            <label>{{ trans('main.email') }}</label>
                            <input type="email" name="email" class="input-field validate" valid-title="Enter your email address" required>
                            <u></u>
                        </div>
                        <div class="form-group tab-con">
                            <input type="checkbox" class="input-r" name="terms" style="display: block;position: relative;top: 16px;width: auto;height: auto" valid-title="If you want to continue please accept terms and rules" required>
                            <label class="label-r" style="margin-left: 15px;">{{ trans('main.i_accept') }} <a href="/page/pages_terms">{{ trans('main.term_rules') }}</a></label>
                        </div>
                        @if(get_option('user_register_captcha') == 1)
                        <div class="form-group tab-con">
                        {!! NoCaptcha::display() !!}
                        </div>
                        @endif
                        <button class="btn btn-custom pull-left btn-register-user btn-register-user-r">{{ trans('main.register') }}</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
@endsection

<style>

.formBox.level-reg .registerBox {
    opacity: 1;
visibility: visible; }
</style>

@section('script')
    
    {!! NoCaptcha::renderJs() !!}
@endsection
