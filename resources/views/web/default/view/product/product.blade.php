@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    - {{ $product->title }}
@endsection
@section('page')

    <div class="container">
        <div class="row product-header">
            <div class="col-xs-12 col-md-8 tab-con">
                <h2 class="violet">{{ $product->title }}</h2>
            </div>
        </div>
    </div>

    <div class="h-20"></div>

    <div class="container">
        <div class="row product-body">
            <div class="col-md-9 col-xs-12 video-details">
                @if($product->type == '3')
                    <?php
                    $current_title = '';
                    if(isset($currentPart)){
                        $current_title = $currentPart->title;
                        echo $currentPart->upload_video;
                    }else{?>
                    <img src="{{asset('/bin/admin/images/teacher-video-thumb.jpg')}}" width="100%">
                    <?php }
                    ?>


                    <div class="video-details-section">
                        <a href="javascript:void(0);" class="course-id-s" title="Course Id.">
                            <span class="playericon mdi mdi-library-video"></span>
                            {{ $current_title }}
                        </a>
                        <a class="pull-left views-s" title="Views" href="javascript:void(0)">
                            <span>{{ $product->view }}</span>
                            <span class="playericon mdi mdi-eye"></span>
                        </a>
                    </div>

                @else
                    <div class="course-thumb">
                        <span class="helper"></span><img src="{!! baseURL() !!}{{ !empty($product->image) ? $product->image : '/bin/admin/images/no-video.png' }}">
                    </div>
                @endif
            </div>
            <div class="col-md-3 col-xs-12 course_details">
                <div class="text-center">
                    <?php echo getProfileBox($profile->id) ?>
                    <?php echo getProfileText($profile->id, false) ?>
                </div>
                <div class="h-10"></div>

                @if (isset($product->price))

                    <div class="product-buy-selection">
                        <form>
                            {{ csrf_field() }}
                            @if(isset($user) && $product->user_id == $user['id'])
                                <a class="btn btn-orange product-btn-buy sbox3" id="buy-btn" href="/user/content/edit/{{ $product->id }}">{{ trans('main.edit_course') }}</a>
                            @elseif(!$buy)
                                @if(!empty($product->price) and $product->price != 0)
                                    <div class="product-user-box-footer">
                                        <a class="" id="buy-btn" href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=video&id='.$product->id)}}">{{ trans('Purchase Course')}} - {{currency($product->price)}}</a>
                                    </div>
                                @endif
                                    @if($product->price == 0)
                                        <div class="product-user-box-footer">
                                            <a class="" id="buy-btn" href="{{\Illuminate\Support\Facades\URL::to('/user/book?type=video&id='.$product->id)}}">{{ trans('Purchase Course')}} - FREE!</a>
                                        </div>
                                    @endif
                            @else
                                @if(!empty($product->price) and $product->price != 0)
                                    <a class="btn btn-orange product-btn-buy sbox3" href="javascript:void(0);">{{ trans('main.purchased_item') }}</a>
                                @endif
                            @endif
                        </form>
                    </div>
                @endif
                <div class="h-10 visible-xs"></div>

            </div>
        </div>
    </div>

    <div class="h-20"></div>
    <div class="container">
        <div class="row">


            <div class="col-md-9 col-xs-12 product-part-container">
                <div class="user-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#tab1" role="tab" data-toggle="tab">{{ trans('main.course_content') }}</a></li>

                        <li><a href="#tab2" role="tab" data-toggle="tab">{{ trans('main.details') }}</a></li>
                    <!--<li><a href="#tab3" role="tab" data-toggle="tab">{{ trans('main.prerequisites') }}</a></li>-->
                        @if (!empty($product->quizzes) and !$product->quizzes->isEmpty())
                            <li><a href="#tab4" role="tab" data-toggle="tab">{{ trans('main.quizzes') }}</a></li>
                        @endif
                        @if (!empty($product->quizzes) and !$product->quizzes->isEmpty() and $hasCertificate)
                            <li><a href="#tab5" role="tab" data-toggle="tab">{{ trans('main.certificates') }}</a></li>
                        @endif
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        <div class="active tab-pane fade in" id="tab1">
                            <ul class="part-ul">
                                @foreach($parts as $part)
                                    <li>
                                        <div class="part-links">
                                            <div class="col-md-2 col-xs-2 tab-con">
                                                @if($buy or $part['preview'] == 1)
                                                    <a href="{{$part['id']}}"><span class="playicon mdi mdi-play-circle"></span></a>
                                                @else
                                                    <span class="playicon mdi mdi-lock"></span>
                                                @endif
                                            </div>
                                            <div class="col-md-8 col-xs-8 tab-con">
                                                <label>{{ $part['title'] }}</label>
                                            </div>
                                            <div class="col-md-2 col-xs-2 hidden-xs tab-con">
                                                <span class="btn btn-gray btn-description " data-toggle="modal" href="#description-{{ $part['id'] }}">{{ trans('main.description') }}</span>
                                                <div class="modal fade" id="description-{{ $part['id'] }}">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal" aria-hidden="true">
                                                                    &times;
                                                                </button>
                                                                <h4 class="modal-title">{{ trans('main.description') }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                {!! $part['description'] !!}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-custom pull-left" data-dismiss="modal">{{ trans('main.close') }}</button>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                            </div>
                                            <!-- <div class="col-md-2 text-center hidden-xs tab-con">
                                                 <span></span>
                                             </div>
                                             <div class="col-md-2 hidden-xs tab-con">
                                                 <span></span>
                                             </div>-->

                                        </div>
                                    </li>
                                @endforeach
                                @if(isset($meta['document']) and $meta['document']!='')
                                    <li class="document">
                                        <div class="col-md-1">
                                            <span class="clip"></span>
                                        </div>
                                        <div class="col-md-10 text-left" style="text-align: left;">
                                            <label>{{ trans('main.documents') }}</label>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <span class="download-part" data-href="{{ $meta['document'] }}"><span class="mdi mdi-arrow-down-bold"></span></span>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <span>{!! $product->content ?? '' !!}</span>
                        </div>

                        <div class="tab-pane fade" id="tab4">
                            @if (!empty($product->quizzes) and !$product->quizzes->isEmpty())
                                @if (!auth()->check())
                                    <div class="col-xs-12 text-center support-lock support-lock-s">
                                        <span>{{ trans('main.login_to_quiz') }}</span>
                                        <br>
                                        <span class="mdi mdi-lock"></span>
                                    </div>
                                @else
                                    <ul class="part-ul">
                                        <li class="row" style="background-color: #343871;color: #ffffff">
                                            <div class="col-md-3 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.quiz_name') }}</span>
                                            </div>
                                            <div class="col-md-2 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.time') }}</span>
                                            </div>

                                            <div class="col-md-3 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.questions') }}</span>
                                            </div>

                                            <div class="col-md-2 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.grade') }}</span>
                                            </div>

                                            <div class="col-md-2 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.controls') }}</span>
                                            </div>
                                        </li>

                                        @foreach ($product->quizzes as $quiz)
                                            <li class="row">
                                                <div class="col-md-3 text-center hidden-xs tab-con">
                                                    <span>{{ $quiz->name }}</span>
                                                    @if ($quiz->certificate)
                                                        <small style="display: block">{{ trans('main.certificate_include') }}</small>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 text-center hidden-xs tab-con">
                                                    <span>{{ (!empty($quiz->time)) ? $quiz->time : trans('main.unlimited') }}</span>
                                                </div>

                                                <div class="col-md-3 text-center hidden-xs tab-con">
                                                    <span>{{ count($quiz->questions) }}</span>
                                                </div>

                                                <div class="col-md-2 text-center hidden-xs tab-con">
                                                    <span style="color: {{ $quiz->result_status == 'pass' ? 'green' : ($quiz->result_status == 'fail' ? 'red' : 'black') }}">{{ ( isset($quiz->user_grade)) ? $quiz->user_grade : 'No grade' }}</span>
                                                </div>

                                                <div class="col-md-2 text-center hidden-xs tab-con">
                                                    <a href="{{ ($quiz->can_try) ? '/user/quizzes/'. $quiz->id .'/start' : ''}}" {{ (!$quiz->can_try) ? 'disabled="disabled"' : '' }} class="btn btn-success btn-round">quizzes</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>
                        <div class="tab-pane fade" id="tab5">
                            @if (!empty($product->quizzes) and !$product->quizzes->isEmpty() and $hasCertificate and $canDownloadCertificate)
                                @if (!auth()->check())
                                    <div class="col-xs-12 text-center support-lock support-lock-s">
                                        <span>{{ trans('main.login_to_quiz') }}</span>
                                        <br>
                                        <span class="mdi mdi-lock"></span>
                                    </div>
                                @else
                                    <ul class="part-ul">
                                        <li class="row" style="background-color: #343871;color: #ffffff">
                                            <div class="col-md-3 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.quiz_name') }}</span>
                                            </div>
                                            <div class="col-md-2 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.quiz_pass_mark') }}</span>
                                            </div>

                                            <div class="col-md-3 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.you_grade') }}</span>
                                            </div>

                                            <div class="col-md-2 text-center hidden-xs tab-con">
                                                <span>{{ trans('main.download') }}</span>
                                            </div>
                                        </li>

                                        @foreach ($product->quizzes as $quiz)
                                            @if (!empty($quiz->result_status) and $quiz->result_status == 'pass')
                                                <li class="row">
                                                    <div class="col-md-3 text-center hidden-xs tab-con">
                                                        <span>{{ $quiz->name }}</span>
                                                    </div>
                                                    <div class="col-md-2 text-center hidden-xs tab-con">
                                                        <span>{{ $quiz->pass_mark }}</span>
                                                    </div>

                                                    <div class="col-md-3 text-center hidden-xs tab-con">
                                                        <span>{{ $quiz->user_grade }}</span>
                                                    </div>

                                                    <div class="col-md-2 text-center hidden-xs tab-con">
                                                        <a href="/user/certificates/{{ $quiz->result->id }}/download" class="btn btn-success">{{ trans('main.download_certificate') }}</a>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>
                        <div class="tab-pane fade" id="tab6">
                            <table class="table table-hover table-bordered" style="margin-bottom: 0;">
                                <thead>
                                <th class="text-center">{!! trans('main.title') !!}</th>
                                <th class="text-center">{!! trans('main.date') !!}/{!! trans('main.time') !!}</th>
                                <th class="text-center">{!! trans('main.duration') !!}</th>
                                <th class="text-center">{!! trans('main.status') !!}</th>
                                </thead>
                                <tbody>
                                @foreach($product->meetings as $meeting)
                                    <tr>
                                        <td class="text-center"><b>{!! $meeting->title ?? '' !!}</b></td>
                                        <td class="text-center">{!! $meeting->date ?? '' !!} {!! $meeting->time ?? '' !!}</td>
                                        <td class="text-center">{!! $meeting->duration ?? '' !!}&nbsp;{!! trans('admin.minutes') !!}</td>
                                        <td class="text-center">{!! '' !!}
                                            <?php
                                            $start = strtotime($meeting->date . ' ' . $meeting->time);
                                            $end = strtotime('+' . $meeting->duration . ' minutes', $start);
                                            $nowtime = strtotime(date('Y-m-d H:i:s', time()));

                                            //echo date('Y-m-d H:i:s',$start).'<br>';
                                            //echo date('Y-m-d H:i:s',$end).'<br>';
                                            //echo date('Y-m-d H:i:s',$nowtime).'<br>';

                                            if ($nowtime >= $start && $nowtime < $end) {
                                                $meeting_link = $meeting->id . '-' . $meeting->content_id . '-' . $meeting->user_id . '-' . $start;
                                                echo '<a href="#" class="btn btn-danger" onclick="startMeeting(' . $meeting_link . ')">Join Class</a>';
                                            }

                                            ?>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="h-20"></div>
            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>

    <div class="h-30"></div>

@endsection
@section('script')
    <script type="application/javascript" src="/assets/default/view/fluid-player-master/fluidplayer.min.js"></script>
    <script>
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
    </script>

@endsection
