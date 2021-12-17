<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <title>Admin Panel - @yield('title') </title>

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('assets/default/vendor/bootstrap/css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/default/vendor/bootstrap/css/bootstrap-3.2.rtl.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/default/vendor/font-awesome/css/font-awesome.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/default/vendor/magnific-popup/magnific-popup.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/default/vendor/bootstrap-datepicker/css/datepicker3.css')}}"/>

    <!-- Page Vendor -->
@yield('style')
<!-- Theme CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/theme.css"/>

    <!-- Skin CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/skins/default.css"/>

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/theme-custom.css">

    <!-- Head Libs -->
    <script src="/assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body">

    <!-- start: header -->

    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
    @yield('sidebar')
    <!-- end: sidebar -->

        <section role="main" class="content-body">
        @yield('pageheader')

        <!-- start: page -->

        @yield('page')

        <!-- end: page -->
        </section>

        @yield('modals')
    </div>

    <!-- Vendor -->
    <script src="/assets/vendor/jquery/jquery.js"></script>
    <script src="/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="/assets/vendor/magnific-popup/magnific-popup.js"></script>
    <script src="/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <script src="/assets/admin/ckeditor/ckeditor.js"></script>
    <script src="/assets/admin/ckeditor/config.js"></script>

@yield('script')
<!-- Specific Page Vendor -->

    <!-- Theme Base, Components and Settings -->
    <script src="/assets/javascripts/theme.js"></script>

    <!-- Theme Custom -->
    <script src="/assets/javascripts/theme.custom.js"></script>

    <!-- Theme Initialization Files -->
    <script src="/assets/javascripts/theme.init.js"></script>

</section>
</body>
</html>
