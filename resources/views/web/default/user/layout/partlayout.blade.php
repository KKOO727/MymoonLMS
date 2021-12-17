@include(getTemplate().'.view.layout.header',['title'=>'User Plan'])
@include(getTemplate().'.user.layout.menu')

<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')"><a href="/user/content/part/new/{{ $id ?? 0 }}">{{ trans('main.new_part') }}</a></li>
                <li class="@yield('tab2')"><a href="/user/content/part/list/{{ $id ?? 0 }}">{{ trans('main.videos_list') }}</a></li>
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
