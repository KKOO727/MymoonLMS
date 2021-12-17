@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout_user.menu')

@section('title','Courses')
<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')">
                    <a href="/user/quizzes">
                        <span class="submicon mdi mdi-newspaper-variant-multiple-outline"></span>
                        {{ trans('main.quizzes') }}
                    </a>
                </li>
                <li class="@yield('tab2')">
                    <a href="/user/certificates">
                        <span class="submicon mdi mdi-certificate"></span>
                        {{ trans('main.certificates') }}
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
