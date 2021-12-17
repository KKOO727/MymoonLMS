<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Admin Panel - @yield('title', '')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="/assets/admin/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/assets/admin/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="/assets/default/vendor/select2/select2.css">
    <link rel="stylesheet" href="/assets/admin/modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="/assets/admin/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">

    @if(get_option('site_rtl','0') == 1)
        <link rel="stylesheet" href="/assets/admin/css/rtl.css"/>
@endif

<!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/admin/css/admin-custom.css">
    <style>
        .custom-switch-input:checked ~ .custom-switch-description {
            position: relative;
            top: 4px;
        }
        .modal-backdrop.show{
            display: none !important;
        }
    </style>
    <!-- Start GA -->
    <link rel="stylesheet" href="//rawgit.com/google/code-prettify/master/src/prettify.css"/>
    <!-- /END GA --></head>

<body>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                {{ csrf_field() }}
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <img alt="image" src="/assets/admin/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                        <div class="d-sm-none d-lg-inline-block">Hi, {!! $Admin['username'] ?? '' !!}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/admin/profile" class="dropdown-item has-icon">
                            <i class="fas fa-user"></i> {!! trans('admin.profile') !!}
                        </a>
                        <a href="/admin/setting/main" class="dropdown-item has-icon">
                            <i class="fas fa-cog"></i> {!! trans('admin.settings') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/logout" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> {!! trans('admin.exit') !!}
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="/admin"><img src="{{url('bin/admin/images/logo/ic_logo_white.png')}}" width="160"></a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="/admin">AP</a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header">Dashboard</li>
                    @if(checkAccess('report'))
                        <li id="report">
                            <a href="/admin/report/user" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                        </li>@endif
                    <li><a class="nav-link" href="/admin/teacherVerify"><i class="fas fa-user"></i><span>{{  trans('admin.identity_verification') }}</span></a></li>
                    <li><a class="nav-link" href="/admin/content/waiting"><i class="fas fa-book"></i> <span>{{  trans('admin.pending_courses') }}</span></a></li>
                    <li class="dropdown" id="ticket">  @if(checkAccess('ticket'))
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-life-ring"></i> <span>Tickets</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/ticket/ticketsopen">Pending</a></li>
                                <li><a class="nav-link" href="/admin/ticket/ticketsclose">Resolved</a></li>
                                <li><a class="nav-link" href="/admin/ticket/category">Ticket categories</a></li>
                                <li><a class="nav-link" href="/admin/ticket/new">New Ticket</a></li>
                            </ul>
                        @endif
                    </li>
                    @if(checkAccess('user'))
                        <li class="dropdown" id="user">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i> <span>{{  trans('admin.users') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/user/lists">{{  trans('admin.student') }}</a></li>
                                <li><a class="nav-link" href="/admin/user/vendor">{{  trans('admin.vendor') }}</a></li>
                            </ul>
                        </li>@endif
                    @if(checkAccess('balance'))
                        <li class="dropdown" id="balance">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-pie"></i> <span>Financial</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/balance/list">Balance Logs</a></li>
                                <li><a class="nav-link @if(isset($alert['withdraw']) and $alert['withdraw'] > 0) beep beep-sidebar @endif" href="/admin/balance/withdraw">{{  trans('admin.withdrawal_list') }}</a></li>
                                <li><a class="nav-link" href="/admin/balance/analyzer">{{  trans('admin.financial_analyser') }}</a></li>
                            </ul>
                        </li>
                    @endif



                    <li class="menu-header">CRM</li>
                    @if(checkAccess('manager'))
                        <li class="dropdown" id="manager">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i> <span>{{  trans('admin.employees') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/manager/lists">{{  trans('admin.list') }}</a></li>
                                <li><a class="nav-link" href="/admin/manager/new">{{  trans('admin.new_employee') }}</a></li>
                            </ul>
                        </li>@endif



                    @if(checkAccess('notification'))
                        <li class="dropdown" id="notification">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-bell"></i> <span>{{  trans('admin.notifications') }}</span></a>
                            <ul class="dropdown-menu">
                            <li><a class="nav-link" href="/admin/notification/template">{{  trans('admin.templates') }}</a></li>
                                <li><a class="nav-link" href="/admin/notification/template/tnew">{{  trans('admin.new_template') }}</a></li>
                                <li><a class="nav-link" href="/admin/notification/new">{{  trans('admin.sms_inbox') }}</a></li>
                                <li><a class="nav-link" href="/admin/notification/list">{{  trans('admin.sent_notifications') }}</a></li>

                            </ul>
                        </li>@endif
                    <li class="menu-header">Content</li>

                    @if(checkAccess('live'))
                        <li class="dropdown" id="live">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-camera"></i> <span>{{  trans('admin.meeting') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/live/list">{{  trans('admin.list') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(checkAccess('blog'))
                        <li class="dropdown" id="blog">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-word"></i> <span>{{  trans('admin.blog_articles') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/blog/posts">{{  trans('admin.blog_posts') }}</a></li>
                                <li><a class="nav-link" href="/admin/blog/post/new">{{  trans('admin.new_post') }}</a></li>
                                <li><a class="nav-link" href="/admin/blog/category">{{  trans('admin.contents_categories') }}</a></li>
                                <li><a class="nav-link" href="/admin/blog/comments">{{  trans('admin.blog_comments') }}</a></li>
                                <li><a class="nav-link" href="/admin/blog/article">{{  trans('admin.articles') }}</a></li>
                            </ul>
                        </li>@endif

                    <li class="menu-header">Financial</li>
                    @if(checkAccess('buysell'))
                        <li id="buysell">
                            <a href="/admin/buysell/list" class="nav-link"><i class="fas fa-shopping-cart"></i> <span>{{  trans('admin.sales') }}</span></a>
                        </li>@endif

                    <li class="menu-header">Marketing</li>
                    @if(checkAccess('email'))
                        <li class="dropdown" id="email">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-envelope"></i> <span>{{  trans('admin.emails') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/email/templates">{{  trans('admin.email_templates') }}</a></li>
                                <li><a class="nav-link" href="/admin/email/template/new">{{  trans('admin.new_template') }}</a></li>
                                <li><a class="nav-link" href="/admin/email/new">{{  trans('admin.send_email') }}</a></li>
                            </ul>
                        </li>@endif
                    @if(checkAccess('discount'))
                        <li class="dropdown" id="discount">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-gift"></i> <span>{{  trans('admin.discounts') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/discount/list">{{  trans('admin.giftcards_list') }}</a></li>
                                <li><a class="nav-link" href="/admin/discount/new">{{  trans('admin.new_giftcard') }}</a></li>
                                <li><a class="nav-link" href="/admin/discount/contentlist">{{  trans('admin.promotions_list') }}</a></li>
                                <li><a class="nav-link" href="/admin/discount/contentnew">{{  trans('admin.new_promotion') }}</a></li>
                            </ul>
                        </li>@endif

                    @if(checkAccess('setting'))
                        <li class="menu-header">Setting & Profile</li>@endif
                    @if(checkAccess('setting'))
                        <li class="dropdown" id="setting">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>{{  trans('admin.settings') }}</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="/admin/setting/main">{{  trans('admin.general_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/display">{{  trans('admin.custom_codes') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/user">{{  trans('admin.users_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/content">{{  trans('admin.course_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/term">{{  trans('admin.rules') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/blog">{{  trans('admin.blog_article_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/notification">{{  trans('admin.notification_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/social">{{  trans('admin.social_networks') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/footer">{{  trans('admin.footer_settings') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/pages">{{  trans('admin.custom_pages') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/default">{{  trans('admin.default_placeholders') }}</a></li>
                                <li><a class="nav-link" href="/admin/setting/view_templates">{{  trans('admin.view_templates') }}</a></li>
                            </ul>
                        </li>@endif
                    <li>
                        <a href="/admin/about" class="nav-link"><i class="fas fa-info"></i> <span>{{  trans('admin.about') }}</span></a>
                    </li>

                    <li>
                        <a href="/admin/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> <span>{{  trans('admin.exit') }}</span></a>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="main-content">
            <div class="section">
                <div class="section-header">
                    <h1>@yield('title', '')</h1>
                    @if(isset($breadcom) and count($breadcom))
                        <div class="section-header-breadcrumb">
                            @foreach($breadcom as $bread)
                                <div class="breadcrumb-item">{!! $bread !!}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="section-body">
                    @yield('page')
                </div>
            </div>
        </div>
        @include('admin.newlayout.modals')
        @yield('modals')
    </div>
</div>
<!-- General JS Scripts -->
<script src="/assets/admin/modules/jquery.min.js"></script>
<script src="/assets/admin/modules/popper.js"></script>
<script src="/assets/admin/modules/tooltip.js"></script>
<script src="/assets/admin/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/admin/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/admin/modules/moment.min.js"></script>
<script src="/assets/admin/js/stisla.js"></script>
<script src="/assets/admin/modules/cleave-js/dist/cleave.min.js"></script>
<script src="/assets/admin/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
<script src="/assets/admin/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="/assets/admin/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/admin/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="/assets/admin/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="/assets/admin/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="/assets/admin/modules/select2/dist/js/select2.full.min.js"></script>
<script src="/assets/admin/modules/jquery-selectric/jquery.selectric.min.js"></script>
<script src="/assets/admin/modules/jquery.sparkline.min.js"></script>
<script src="/assets/admin/modules/chart.min.js"></script>
<script src="/assets/admin/modules/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="/assets/admin/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="/assets/admin/modules/jqvmap/dist/maps/jquery.vmap.indonesia.js"></script>
<script src="/assets/admin/modules/datatables/datatables.min.js"></script>
<script src="/assets/admin/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/admin/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
<script src="/assets/admin/modules/jquery-ui/jquery-ui.min.js"></script>
<script src="/assets/admin/modules/summernote/summernote.min.js"></script>
<script src="/assets/admin/modules/summernote/plugin/summernote-ext-codewrapper-master/dist/summernote-ext-codewrapper.min.js"></script>
<script src="/assets/admin/modules/summernote/plugin/summernote-ext-highlight-master/src/summernote-ext-highlight.js"></script>
<script src="/assets/admin/modules/jquery-selectric/jquery.selectric.min.js"></script>
<script src="/assets/admin/modules/upload-preview/assets/js/jquery.uploadPreview.min.js"></script>
<script src="/assets/admin/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="/assets/admin/modules/select2/dist/js/select2.full.min.js"></script>
<script src="/assets/admin/modules/jquery-selectric/jquery.selectric.min.js"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script src="/assets/admin/js/scripts.js"></script>
<script src="/assets/admin/js/custom.js"></script>
<script>
    $('.lfm_image').filemanager('file',{prefix: '/admin/laravel-filemanager'});
    $('#datatable-details').dataTable();

    @if(isset($menu))
    $(function () {
        $('#{!! $menu !!}').addClass('active');
    });
    @endif
    @if(isset($url))

    $(function () {
        $('.nav-link').each(function () {
            if ('{!! url('/') !!}' + $(this).attr('href') == '{!! $url !!}') {
                $(this).parent().addClass('active');
            }
        })
    });
    @endif
</script>
@yield('script')
</body>
</html>
