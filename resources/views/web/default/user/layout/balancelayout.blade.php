@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout.menu')

@section('title','User Panel')
<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')">
                    <a href="/user/balance/">
                        <span class="submicon mdi mdi-podium-gold"></span>
                        {{ trans('main.my_sales') }}
                    </a>
                </li>
                <li class="@yield('tab2')">
                    <a href="/user/balance/sell/post">
                        <span class="submicon mdi mdi-truck-fast"></span>
                        {{ trans('main.pending_sales') }}
                    </a>
                </li>
                <li class="@yield('tab3')">
                    <a href="/user/balance/log">
                        <span class="submicon mdi mdi-point-of-sale"></span>
                        {{ trans('main.financial_documents') }}
                    </a>
                </li>
                <li class="@yield('tab4')">
                    <a href="/user/balance/charge">
                        <span class="submicon mdi mdi-credit-card-plus"></span>
                        {{ trans('main.charge_account') }}
                    </a>
                </li>
                <li class="@yield('tab5')">
                    <a href="/user/balance/report">
                        <span class="submicon mdi mdi-chart-areaspline"></span>
                        {{ trans('main.reports') }}
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
