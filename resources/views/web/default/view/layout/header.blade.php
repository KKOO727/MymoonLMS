<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{!! get_option('site_description','') !!}">
    <link rel="icon" href="{{url('/assets/default/images/favicon.ico')}}" type="image/png" sizes="32x32">

    <link rel="stylesheet" href="{{url('/assets/default/vendor/bootstrap/css/bootstrap.min.css')}}"/>
    <!--<link rel="stylesheet" href="{{url('/assets/default/vendor/bootstrap/css/bootstrap-3.2.rtl.css')}}"/>-->
    <link rel="stylesheet" href="{{url('/assets/default/vendor/font-awesome/css/font-awesome.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/raty/jquery.raty.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/view/fluid-player-master/fluidplayer.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/select2/select2.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/select2/select2-bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/fullcalendar/main.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/simplepagination/simplePagination.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/easyautocomplete/easy-autocomplete.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/jquery-te/jquery-te-1.4.0.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/jquery-ui/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/owlcarousel/dist/assets/owl.carousel.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/owlcarousel/dist/assets/owl.theme.default.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/stylesheets/vendor/mdi/css/materialdesignicons.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/telephoneCode/intlInputPhone.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/vendor/fancybox/fancybox.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/fontawesome/css/all.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/stylesheets/view-custom.css')}}?time={!! time() !!}"/>
    <link rel="stylesheet" href="{{url('/assets/default/stylesheets/mymoon.css')}}"/>
    <link rel="stylesheet" href="{{url('/assets/default/stylesheets/view-responsive.css')}}"/>
    <title>@yield('title'){!! $title ?? '' !!}</title>
    @yield('style')
    <style>
        .menu-header ul li a {
            color: #fff
        }
        .navbar-inverse {background-color:#384047}

        @keyframe move {
            from { right: 0%; }
            to { right: 100%; }
        }

    </style>
    @if(Request::is('/'))
        <style>
            #header-menu-section {
                width: 100%;
                background: rgba(0, 0, 0, .3);
            }
            .navbar-brand {height:auto;padding:0;}
            .navbar-header {
                background-color: transparent;
                border-radius: 0;
            }
            .navbar-default .navbar-nav > li > a,.navbar-default .navbar-nav > li > a:hover,.navbar-default .navbar-nav > li > a:focus {
                color: #fff;
                font-size:1.1em
            }
            .navbar-default .navbar-nav > .open > a,
            .navbar-default .navbar-nav > .open > a:hover,
            .navbar-default .navbar-nav > .open > a:focus {
                color:#fff;
            }
            .navbar-fixed-top.scrolled {
                background-color: #932680 !important;
                transition: background-color 200ms linear;
            }
        </style>
    @else
        <style>
            #header-menu-section {
                width: 100%;
                background: #fff;
                border-bottom: 1px solid #e1e1e1;
            }
            .navbar-brand { 
                height:auto;padding: 0;
            }
        </style>
    @endif

    <script type="text/javascript">
        function openNav() {
            document.getElementById("menu").style.width = "100%";
            $('#menu').css('box-shadow', '0 0 0 max(100vh, 100vw) rgba(0, 0, 0, .4)');
        }

        function closeNav() {
            document.getElementById("menu").style.width = "0";
            $('#menu').css('box-shadow', 'none');
        }

        function openFilter() {
            document.getElementById("filters-menu").style.width = "100%";
            $('#filters-menu').css('box-shadow', '0 0 0 max(100vh, 100vw) rgba(0, 0, 0, .4)');
        }

        function closeFilter() {
            document.getElementById("filters-menu").style.width = "0";
            $('#filters-menu').css('box-shadow', 'none');
        }
    </script>
</head>
<body>
@if(Request::is('/'))
    <div class="modal fade" id="betaModal" style="z-index:99999 !important">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border:none">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body" style="background:#932680;font-size:24px;color:#fff;text-align:center;padding:40px;">
                    <img src="{{baseURL().'/bin/admin/images/logo/ic_logo_white.png'}}" alt="{{ get_option('site_title') }}" style="width: 190px;"/><br><br>
                    Welcome to the Beta version of the new MyMoon website. <br>Intended for final public testing before the launch of the first “real” version of the new website
                </div>

            </div>
        </div>
    </div>
    @endif

<div class="modal fade" id="jitsiModal" style="z-index:99999 !important">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">
            <div class="modal-header">
                <p>Classroom <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="location.reload()">&times;</button></p>

            </div>
            <div class="modal-body">
                <div id="meet"></div>
            </div>

        </div>
    </div>
</div>
<div class="hamcontent hidden-md hidden-lg mobile-menu">
    <nav role="navigation" class="ham">
        <div id="menuToggle" class="pull-right">
            <input type="checkbox" onclick="openNav()" />
            <span></span>
            <span></span>
            <span></span>
            <div id="menu">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fa fa-times"></i></a>
                @include(getTemplate() . '.view.layout.mainMenu')
            </div>
        </div>
        <div class="pull-left">
            <a class="" href="{{url('/')}}">
                <img src="{{url('/bin/admin/images/logo/main-logo.svg')}}" alt="{{ get_option('site_title') }}"
                    class="logo-type" style="width: 160px; padding: 0px 20px 0 5px; margin-left: 10px;  margin-top: 10%;" />
            </a>
        </div>
    </nav>
</div>
<nav class="navbar navbar-default navbar-fixed-top visible-md visible-lg visible-xl" id="header-menu-section">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('/')}}">
                <img
                    src="@if(Request::is('/')){{baseURL().'/bin/admin/images/logo/ic_logo_white.png'}}@else {{baseURL().'/bin/admin/images/logo/main-logo.svg'}}@endif"
                    alt="{{ get_option('site_title') }}"
                    class="logo-type" style="width: 190px;padding: 5px 0px 5px 0px;"/></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @include(getTemplate() . '.view.layout.mainMenu')
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
