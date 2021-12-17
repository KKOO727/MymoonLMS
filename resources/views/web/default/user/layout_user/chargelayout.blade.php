@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout_user.menu')

@section('title','User Panel')
<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <div class="tab-content">
                <div class="active tab-pane fade in" id="tab1">
                    @yield('tab')
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>$('#charge-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
