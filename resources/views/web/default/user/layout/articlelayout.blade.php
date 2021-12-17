@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout.menu')

<div class="h-20"></div>
<div class="container-fluid">
    <div class="container">
        <div class="col-md-12 col-xs-12">
            <ul class="nav nav-tabs nav-justified panel-tabs" role="tablist">
                <li class="@yield('tab1')">
                    <a href="/user/article/new" id="newarticle">
                        <span class="submicon mdi mdi-book-plus"></span>
                        {{ trans('main.new_article') }}
                    </a>
                </li>
                <li class="@yield('tab2')">
                    <a href="/user/article/list">
                        <span class="submicon mdi mdi-book"></span>
                        {{ trans('main.my_articles') }}
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
    <script type="text/javascript" src="/assets/default/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            CKEDITOR.config.language = 'fa';
        });
    </script>
    <script>$('#article-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
