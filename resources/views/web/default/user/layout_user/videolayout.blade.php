@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout_user.menu')

@section('title','Courses')
<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')">
                    <a href="/user/video/buy">
                        <span class="submicon mdi mdi-cloud-download"></span>
                        {{ trans('main.my_purchases') }}
                    </a>
                </li>
                <li class="@yield('tab2')">
                    <a href="/user/video/subscribe">
                        <span class="submicon mdi mdi-video-plus"></span>
                        {{ trans('main.subscribed') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane fade in" id="tab1">
                    @yield('tab')
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>$('#buy-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
