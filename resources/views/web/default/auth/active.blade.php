@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : 'Website title' }}
    {{ trans('main.active_account') }} -
@endsection
@section('page')
    <div class="h-25"></div>
    <div class="h-25"></div>
    <div class="col-md-4 col-md-offset-4 col-xs-12">
        <div class="ucp-section-box">
            <div class="header back-orange">{{ trans('main.activation') }}</div>
            <div class="body">
                <p>{{ trans('main.account_activation_success') }}</p>
                @if($type =='Teacher')
                <p>
                    Assalam alaikum and Hi,<br>
                    Thank you for applying to join MyMoon. We’re thrilled to have you as a part of the MyMoon teaching staff and can’t wait to see you begin.<br><br>
                    All we have to do now is verify and approve your application, and you can move on to creating a profile Insha’Allah.<br><br>
                    In the meanwhile, find out more about MyMoon and its mission.<br><br>
                    You’ll soon be a part of it. <br>
                    Exciting, isn’t it! :D <br><br>
                    Welcome to MyMoon.<br>
                </p>
                    @endif
            </div>
        </div>
    </div>
    <div class="h-10 clearfix"></div>
@endsection
