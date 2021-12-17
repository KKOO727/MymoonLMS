@extends(getTemplate().'.view.layout.layout')
@section('title')
    {!! $setting['site']['site_title'].'Profile-'.$profile->name !!}
@endsection
@section('page')
    <?php
    $base_url = baseURL();
    $userMetas = get_all_user_meta($profile->id);
    ?>
    <div class="h-20"></div>
    <div class="h-20"></div>

    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">
                @if($agent->isMobile())
                    <div class="col-md-8 col-xs-12 video-details">
                        <?php
                            if(!isset($userMetas['meta_intro_video'])){
                                $userMetas['meta_intro_video']='';
                            }
                            echo getVideoPlayer($userMetas['meta_intro_video'],'100%','320');
                        ?>

                        <div class="teacher-row">
                            <div class="row d-flex">
                                <div class="col-md-3">
                                    <?php echo getProfileBox($profile->id); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php echo getProfileText($profile->id, false); ?>
                                    <?php echo getTeacherRatingForMobile($profile->id); ?>
                                </div>
                            </div>
                            <br>

                            <div class="row col-md-12">
                                <?= getProfileForMobile($profile->id); ?>
                            </div>

                            <div class="course_details profile-book">
                                <div class="teacher-rate">
                                    <?php
                                        if(!isset($userMetas['meta_hourly_rate']))
                                            $userMetas['meta_hourly_rate']=0;
                                    ?>

                                    <h3>{{currency($userMetas['meta_hourly_rate'])}}
                                        <span style="color:#cccccc;font-size:16px">/hour</span>
                                    </h3>
                                </div>

                                <a href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=onetoone&id='.$profile->id)}}" class="btn btn-primary btn-block">BOOK NOW</a>

                                <a href="{{url('/user/addToFavourite?id='.$profile->id)}}" class="btn btn-default btn-block mt-4">
                                    <i class="fas fa-heart"></i> Save to my favourite list
                                </a>
                                <a href="{{url('/user/messages?type=contact&id='.$profile->id)}}" class="btn btn-success btn-block mt-4"> 
                                    <i class="fas fa-email"></i> Contact teacher
                                </a>

                                <div class="h-10"></div>
                                <div class="h-10 visible-xs"></div>
                            </div>

                            <hr>
                            <h3 style="font-weight: bold;">About Me</h3>
                            <span>{{ @$userMetas['meta_short_biography'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="col-md-8 col-xs-12 video-details">
                        <?php
                        if(!isset($userMetas['meta_intro_video'])){
                            $userMetas['meta_intro_video']='';
                        }
                        echo getVideoPlayer($userMetas['meta_intro_video'],'100%','320');
                        ?>

                        <div class="teacher-row">
                            <div class="row">
                                <div class="col-md-9">
                                    <?php echo getProfileText($profile->id,true);?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo getProfileBox($profile->id);?>
                                    <?php echo getTeacherRating($profile->id);?>
                                </div>
                            </div>
                            <hr>
                            <h3 style="font-weight:bold">About Me</h3>
                            <span>{{ @$userMetas['meta_short_biography'] }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="course_details profile-book">
                            <div class="teacher-rate">
                                <?php
                                if(!isset($userMetas['meta_hourly_rate']))
                                    $userMetas['meta_hourly_rate']=0;
                                ?>

                                <h3>{{currency($userMetas['meta_hourly_rate'])}}<span style="color:#cccccc;font-size:16px">/hour</span></h3>
                            </div>
                            <a href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=onetoone&id='.$profile->id)}}" class="btn btn-primary btn-block">BOOK NOW</a>


                            <a href="{{url('/user/addToFavourite?id='.$profile->id)}}" class="btn btn-default btn-block mt-4"> 
                                <i class="fas fa-heart"></i> Save to my favourite list
                            </a>
                            <a href="{{url('/user/messages?type=contact&id='.$profile->id)}}" class="btn btn-success btn-block mt-4"> 
                                <i class="fas fa-email"></i> Contact teacher
                            </a>

                            <div class="h-10"></div>
                            <div class="h-10 visible-xs"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="h-20"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">

                <div class="col-md-8 col-xs-12 product-part-container">
                    <div class="light-box">
                        <h4 class="bold no-margin">Schedule</h4><br>
                        <div id="calendar"></div>
                    </div>
                    <div class="h-20"></div>

                    <div class="light-box">
                        <h4 class="bold no-margin">Resume</h4>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#cv" role="tab" data-toggle="tab">{{ trans('CV') }}</a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab">{{ trans('Certifications') }}</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in text-center" id="cv">
                                @if(!empty($userMetas['meta_resume_file']))
                                    <div class="cv-box">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a class="btn btn-primary" style="margin-top:10px" href="{{ asset($userMetas['meta_resume_file']) }}" target="_blank"><i class="fa fa-file"></i> View CV</a>                                                                                           </button>
                                            </div>
                                            <div class="col-md-7">
                                                <h4 style="text-align:left;padding-left:10px;line-height:30px">CV of {{$profile->name}}</h4>
                                            </div>
                                            <div class="col-md-2">
                                                <img src="{!! baseURL() !!}{{'/assets/default/images/cv-icon.png' }}" style="max-width:100%;width:50px">                                                                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h4 class="text-center">No data found</h4>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="tab2">

                                @if(!empty($userMetas['meta_certificates_file']))
                                    @php
                                        $certs=explode(',',$userMetas['meta_certificates_file']);
                                    @endphp
                                    <div class="row">
                                        @foreach($certs as $cert)
                                            <div class="col-md-4">
                                                <div class="cert-box">
                                                    <a data-fancybox="gallery" data-src="{{asset($cert)}}"> <img src="{{asset($cert)}}"/></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <h4 class="text-center">No data found</h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="light-box">
                        <h4 class="bold no-margin"><i class="fa fa-user"></i> Monthly Plans</h4>
                        <br>

                        @if(isset($plans) && $plans->count() > 0)
                            @foreach($plans as $plan)

                                <div class="plan one">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>{{$plan->title}}</h4>
                                            <div class="plan-content">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Classes per week:</strong> {{$plan->class_number}}</td>
                                                        <td><strong>Classes duration:</strong> {{$plan->duration}} Minutes</td>
                                                        <td width="100"><a class="btn btn-default" href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=plan&id='.$plan->id.'&dur=30')}}">
                                                                {{currency($plan->price)}}
                                                            </a></td>
                                                    </tr>
                                                </table>
                                            </div>


                                        </div>

                                    </div>
                                </div>

                            @endforeach
                        @endif
                    </div>
                    <div class="light-box">
                        <h4 class="bold no-margin"><i class="fa fa-users blue"></i> Group Courses</h4>
                        <br>
                        @if(isset($groups) && $groups->count() > 0)
                            @foreach($groups as $group)
                                @if(time() < strtotime($group->date_to))
                                <div class="plan group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>{{$group->title}}</h4>
                                            <div class="plan-content">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>From:</strong> {{$group->date_from}} <strong>To:</strong> {{$group->date_to}}</td>
                                                        <td><strong>Total Seats:</strong> {{$group->max_student}}<br>
                                                            <strong>Seats Available:</strong> {{$group->seats_available}}
                                                        </td>
                                                        <td width="100"><a class="btn btn-default" href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=group&id='.$group->id)}}">{{currency($group->price)}}
                                                            </a>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="light-box">
                        <h4 class="bold no-margin"><i class="fa fa-video yellow"></i> Video Courses</h4>
                        <br>
                        @if(isset($videos) && $videos->count() > 0)
                            @foreach($videos as $video)

                                <div class="plan video">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <h4>{{$video->title}}</h4>
                                            <div class="plan-content">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Total Videos:</strong> {{$vid_meta[$video->id]['total']}}</td>
                                                    <td></td>
                                                    <td width="100"><a class="btn btn-default" href="{{\Illuminate\Support\Facades\URL::to('/product/'.$video->id)}}">Details</a>
                                                    </td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="light-box">
                        <h4 class="bold no-margin">Reviews</h4>
                        <div class="review-list">
                            @foreach($reviews as $review)
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="profileImageBox" style="background:url('{{get_user_meta($review->student_id,'meta_avatar','/assets/admin/img/avatar/avatar-male.jpeg')}}')">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <h4 class="">{{get_username($review->student_id)}}</h4>
                                        @for($i=1;$i<=5;$i++)
                                        <i class="fa fa-star  @if($i <= $review->score){{'golden'}}@endif"></i>
                                        @endfor
                                            <br><br>
                                        <!--{{date('F j, Y',strtotime($review->created_at))}}<br>-->
                                        {{$review->feedback}}
                                    </div>
                                </div>
                                @endforeach

                        </div>

                        {{--                        <h4 class="text-center">No reviews found</h4>--}}
                    </div>

                </div>
                <div class="col-md-4 col-xs-12"></div>
            </div>
        </div>
    </div>
    <div class="h-30"></div>


@endsection
@section('script')
    <script type="application/javascript" src="/assets/default/view/fluid-player-master/fluidplayer.min.js"></script>
    <script>
        /*
        $(function () {
            fluidPlayer("myDiv", {
                layoutControls: {
                    posterImage: '{!! !empty($meta['cover']) ? $meta['cover'] : '' !!}',
                    logo: {
                        imageUrl: '{!! get_option('video_watermark','') !!}', // Default null
                        position: 'top right', // Default 'top left'
                        clickUrl: '{!! url('/') !!}', // Default null
                        opacity: 0.9, // Default 1
                        imageMargin: '10px', // Default '2px'
                        hideWithControls: true, // Default false
                        showOverAds: 'true' // Default false
                    }
                },
                @if(get_option('site_videoads',0) == 1)
        vastOptions: {
            vastTimeout: {!! get_option('site_videoads_time',5) * 1000 !!},
                    adList: [
                        {
                            roll: '{!! get_option('site_videoads_roll_type','preRoll') !!}',
                            vastTag: '{!! get_option('site_videoads_source') !!}',
                            adText: '{!! get_option('site_videoads_title') !!}',
                        }
                    ]
                }
                @endif
        });
    });

         */
    </script>
    <script>
        $('.raty').raty({
            starType: 'i', score: 5, click: function (rate) {
                window.location = window.location.href + '/rate/' + rate;
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                contentHeight: 400,
                allDaySlot: false,
                dayHeaderContent: (args) => {
                    return moment(args.date).format('ddd D/M')
                },
                validRange: {
                    start: '{{date('Y-m-d')}}',
                },
                weekNumberCalculation: 'ISO',
                displayEventTime: false,
                timeZone: 'local',
                editable: true,
                initialView: 'timeGridWeek',
                nowIndicator: true,
                headerToolbar: {
                    right: 'prev,next',
                    center: 'content',
                },

                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                selectHelper: true,
                events: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar?user='.$profile->id)}}",
                eventClick: function (info) {
                },
                select: function (start, end, allDay) {
                },
            });

            calendar.render();
        });

        function displayMessage(message) {
            $(".response").html("<div class='success'>" + message + "</div>");
            setInterval(function () {
                $(".success").fadeOut();
            }, 1000);
        }


    </script>

@endsection

