@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.videolayout' : getTemplate() . '.user.layout_user.requestlayout')

@section('tab6','active')
@section('tab')
        <ul class="nav nav-tabs nav-justified panel-tabs" style="margin-top: 45px" role="tablist">
            <li class="@yield('video_tab1')">
                <a href="/user/video/request">
                    <span class="submicon mdi mdi-camera-enhance"></span>
                    {{ trans('main.requests') }}</a>
                </a>
            </li>

            <li class="@yield('video_tab2')">
                <a href="/user/video/record">
                    <span class="submicon mdi mdi-video"></span>
                    {{ trans('main.future_courses') }}
                </a>
            </li>
        </ul>

    <div class="tab-content">
        <div class="active tab-pane fade in" id="tab1">
            @yield('video_tab')
        </div>
    </div>
@endsection

