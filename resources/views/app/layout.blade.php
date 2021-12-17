<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/assets/default/vendor/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap/css/bootstrap-3.2.rtl.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/owlcarousel/dist/assets/owl.carousel.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/raty/jquery.raty.css"/>
    <link rel="stylesheet" href="/assets/default/view/fluid-player-master/fluidplayer.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/simplepagination/simplePagination.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/easyautocomplete/easy-autocomplete.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css"/>
    <link rel="stylesheet" href="/assets/default/vendor/jquery-te/jquery-te-1.4.0.css"/>
    <link rel="stylesheet" href="/assets/default/stylesheets/vendor/mdi/css/materialdesignicons.min.css"/>
    @if(get_option('site_rtl','0') == 1)
        <link rel="stylesheet" href="/assets/default/stylesheets/view-custom-rtl.css"/>
    @else
        <link rel="stylesheet" href="/assets/default/stylesheets/view-custom.css?time={!! time() !!}"/>
    @endif
    <link rel="stylesheet" href="/assets/default/stylesheets/view-responsive.css"/>
    <script type="application/javascript" src="/assets/default/vendor/jquery/jquery.min.js"></script>
    @yield('style')
    <title>App</title>
</head>
<body>
    @yield('page')

    <script type="application/javascript" src="/assets/default/vendor/jquery-ui/js/jquery-1.10.2.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/justgage/raphael-2.1.4.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/justgage/justgage.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/simplepagination/jquery.simplePagination.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/onloader/js/jquery.oLoader.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/ios7-switch/ios7-switch.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/sticky/jquery.sticky.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/chartjs/Chart.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/bootstrap-notify-master/bootstrap-notify.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/auto-numeric/autoNumeric.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/raty/jquery.raty.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/owlcarousel/dist/owl.carousel.min.js"></script>
    <script type="application/javascript" src="/assets/default/vendor/jquery-te/jquery-te-1.4.0.min.js"></script>
    <script type="application/javascript" src="/assets/default/clock-counter/jquery.flipTimer.js"></script>
    @yield('script')
</body>
</html>
