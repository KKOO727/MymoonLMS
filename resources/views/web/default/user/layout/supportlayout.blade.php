@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout.menu')

<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')">
                    <a href="/user/ticket/support">
                        <span class="submicon mdi mdi-headset"></span>
                        {{ trans('main.courses_sup') }}
                    </a>
                </li>
                <li class="@yield('tab2')">
                    <a href="/user/ticket/comments">
                        <span class="submicon mdi mdi-message-bulleted"></span>
                        {{ trans('main.comments') }}
                    </a>
                </li>
                <li class="@yield('tab3')">
                    <a href="/user/ticket">
                        <span class="submicon mdi mdi-comment-multiple"></span>
                        {{ trans('main.sup_tickets') }}
                    </a>
                </li>
                <li class="@yield('tab4')">
                    <a href="/user/ticket/notification">
                        <span class="submicon mdi mdi-bell-alert"></span>
                        {{ trans('main.notifications') }}
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
    <script>$('#ticket-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
