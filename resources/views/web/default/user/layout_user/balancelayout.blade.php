@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout_user.menu')

@section('title','User Panel')
<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab6')">
                    <a href="/user/balance/log">
                        <span class="submicon mdi mdi-finance"></span>
                        {{ trans('Balance Logs') }}
                    </a>
                </li>
                <li class="@yield('tab7')">
                    <a data-toggle="modal" href="#topUpModal">
                        <span class="submicon mdi mdi-credit-card-plus"></span>
                        {{ trans('Add MyMoon Credit') }}
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
    <script>$('#balance-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
