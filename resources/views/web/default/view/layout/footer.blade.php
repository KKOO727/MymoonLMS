</div>
<div class="container-fluid" id="footer">
    <div class="container">
        <div class="col-md-3 col-xs-12 tab-con">
            <h4>{{ get_option('footer_col1_title') }}</h4>
            <p>{!! get_option('footer_col1_content') !!}</p>
            <ul>
                <li><a href="/page/about">What is MyMoon?</a></li>
                <li><a href="/page/about">Meet the team</a></li>
                <li><a href="/page/contact">Contact us</a></li>
            </ul>
        </div>
        <div class="col-md-3 col-xs-12 tab-con">
            <h4>{{ get_option('footer_col2_title') }}</h4>
            <p>{!! get_option('footer_col2_content') !!}</p>
            <ul>
                <li><a href="/blog">MyMoon Blog</a></li>
                <li><a href="/page/freeMaterials">Free Material</a></li>
                <li><a href="/page/support">Support us</a></li>
            </ul>
        </div>
        <div class="col-md-3 col-xs-12 tab-con">
            <h4>{{ get_option('footer_col3_title') }}</h4>
            <p>{!! get_option('footer_col3_content') !!}</p>
            <ul>
                <li><a href="/faqs">FAQ</a></li>
                <li><a href="/search">Find a Teacher</a></li>
                <li><a href="/registerTeacher">Become a Teacher</a></li>
            </ul>
        </div>
        <div class="col-md-3 col-xs-12 tab-con">
            <h4>{{ get_option('footer_col4_title') }}</h4>
            <p>{!! get_option('footer_col4_content') !!}</p>
            <ul class="footer-social">
                @foreach($socials as $social)
                    <li><a href="{{$social->link}}"><img src="{{$social->icon}}"> {{$social->title}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="container-fluid footer-blow">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <img src="{{baseURL().'/bin/admin/images/logo/ic_logo_white.png'}}" style="height:45px"/>
            </div>

            <div class="col-md-6 col-xs-12">
                <div style="padding-top:15px;text-align:center">
                    <a href="/page/terms">Terms & Conditions</a> | <a href="/page/terms">Privacy Policy</a>
                </div>
            </div>
            <div class="col-md-3 col-xs-12">
                <div style="padding-top:15px;">
                    Copyright ?? 2021 My Moon
                </div>
            </div>
        </div>
    </div>
</div>

@if(get_option('site_popup',0) == '1')
    <div class="modal fade" id="site_popup">
        <div class="modal-dialog popup_modal">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa fa-close" data-dismiss="modal"></i>
                    <a href="{!! baseURL() !!}{{ get_option('popup_url','javascript:void(0);') }}"><img
                            src="{{ get_option('popup_image','') }}" class="img-responsive"></a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Scripts -->
<script src="{{url('/assets/default/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{url('/assets/default/vendor/justgage/raphael-2.1.4.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/justgage/justgage.js')}}"></script>
<script src="{{url('/assets/default/vendor/simplepagination/jquery.simplePagination.js')}}"></script>
<script src="{{url('/assets/default/vendor/onloader/js/jquery.oLoader.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/ios7-switch/ios7-switch.js')}}"></script>
<script src="{{url('/assets/default/vendor/sticky/jquery.sticky.js')}}"></script>
<script src="{{url('/assets/default/vendor/chartjs/Chart.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/bootstrap-notify-master/bootstrap-notify.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/auto-numeric/autoNumeric.js')}}"></script>
<script src="{{url('/assets/default/vendor/raty/jquery.raty.js')}}"></script>
<script src="{{url('/assets/default/vendor/select2/select2.min.js')}}"></script>
<script src="{{url('/assets/default/fullcalendar/moment.min.js')}}"></script>
<script src="{{url('/assets/default/fullcalendar/main.js')}}"></script>
<script src="{{url('/assets/default/vendor/easyautocomplete/jquery.easy-autocomplete.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script src="{{url('/assets/default/vendor/owlcarousel/dist/owl.carousel.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/jquery-te/jquery-te-1.4.0.min.js')}}"></script>
<script src="{{url('/assets/default/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{url('/assets/default/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{url('/assets/default/input-mask/jquery.inputmask.extensions.js')}}"></script>
<script src="{{url('/assets/default/telephoneCode/intlInputPhone.min.js')}}"></script>
<script src="{{url('/assets/default/vendor/fancybox/fancybox.umd.js')}}"></script>
<?php
use Illuminate\Support\Facades\Auth;
$user=Auth::user();
?>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(), 'guest' => !auth()->check()]); ?>;

</script>
<script src="{{url('/assets/default/javascripts/view-custom.js')}}?time={!! time() !!}"></script>
@if($user != null)
    <script src="https://c1.mymoononline.com/external_api.js"></script>
    <script>
        function startMeeting(roomName) {
            console.log(roomName);

            var url = "https://c1.mymoononline.com/"+roomName;
            window.open(url,'_blank');
            /*
            $("#jitsiModal").modal({
                backdrop: 'static',
                keyboard: false},'show');
            const domain = 'c1.mymoononline.com';
            const options = {
                roomName: roomName,
                width: '100%',
                height: 600,
                configOverwrite: {},
                interfaceConfigOverwrite: {},
                userInfo: {
                    email: '<?php echo $user->email;?>',
                    displayName: '<?php echo $user->name;?>'
                },
                dropbox: {
                    appKey: 'p2acewkde9ci6h5',
                    redirectURI: 'https://c1.mymoononline.com/static/oauth.html'
                },
                parentNode: document.querySelector('#meet')
            };
            const api = new JitsiMeetExternalAPI(domain, options);
            */
            /*
            api.executeCommand('startRecording', {
                mode: 'file', //recording mode, either `file` or `stream`.
                dropboxToken: 'sl.Aubyf6eK5AbznMcjue8tZnPpMLUAoLjPuzr875KATJOdpkysFkQ75Sce62q-iE6LYefB8QutMeRfQxaeJmQZjRvzTJGzMoYeTdoWB9Zr_jB841hEZESuIgckIOO_dcwO3seqN40', //dropbox oauth2 token.

            });

             */
        }
    </script>
@endif
<script>
    $(function () {
        $(document).scroll(function () {
            var $nav = $(".navbar-fixed-top");
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });

        @if(Request::is('/'))
        $("#betaModal").modal('show');
        @endif

        $("[data-mask]").inputmask();
        $(".select2").select2();

        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-70:+1"
        });
        $("#date_from").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            minDate: new Date(),
            onClose: function (selectedDate) {
                $("#date_to").datepicker("option", "minDate", selectedDate);
                var maxdate = new Date();
                maxdate.setMonth(maxdate.getMonth() + 12);
                console.log(maxdate);
                $("#date_to").datepicker("option", "maxDate", maxdate);
            }
        });
        $("#date_to").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            minDate: new Date(),
            onClose: function (selectedDate) {
                $("#date_from").datepicker("option", "maxDate", selectedDate);
            }
        });
        $(".teacher-carousel").owlCarousel({
            items: 4,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
            navClass: ['nav-right', 'nav-left'],
            nav: false,
            autoplay: true,
            autoplayTimeout: 12000,
            dots: true,
            loop: true,
            margin: 10,
            responsive: {
                0: {
                    items: 1,
                },
                480: {
                    items: 1,
                },
                762: {
                    items: 1,
                },
                992: {
                    items: 4,
                }
            }
        });
        $(".testimonial-carousel").owlCarousel({
            items: 3,
            nav: false,
            autoplay: true,
            autoplayTimeout: 12000,
            dots: true,
            loop: true,
            margin: 15,
            responsive: {
                0: {
                    items: 1,
                },
                480: {
                    items: 1,
                },
                762: {
                    items: 2,
                },
                992: {
                    items: 3,
                }
            }
        });
        $(".course-carousel").owlCarousel({
            items: 4,
            /*navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
            navClass: ['nav-right', 'nav-left'],*/
            nav: false,
            autoplay: true,
            autoplayTimeout: 12000,
            loop: true,
            responsive: {
                0: {
                    items: 1,
                },
                480: {
                    items: 1,
                },
                762: {
                    items: 2,
                },
                992: {
                    items: 4,
                }
            }
        });

    });
</script>


@yield('script')
@if(session('msg') != null)
    <script>
        $.notify({
            message: '{{ session('msg')}}'
        }, {
            type: 'danger',
            allow_dismiss: false,
            z_index: '99999999',
            placement: {
                from: "bottom",
                align: "right"
            },
            position: 'fixed'
        });
    </script>
@endif
<script>
    function changeLang(locale) {
        $.ajax({
            url: "{{\Illuminate\Support\Facades\URL::to('/localeUpdate')}}",
            data: 'locale=' + locale,
            type: "POST",
            success: function (data) {
                location.reload();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    function changeCurrency(cur) {
        $.ajax({
            url: "{{\Illuminate\Support\Facades\URL::to('/currencyUpdate')}}",
            data: 'cur=' + cur,
            type: "POST",
            success: function (data) {
                location.reload();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
</script>
</body>
</html>
