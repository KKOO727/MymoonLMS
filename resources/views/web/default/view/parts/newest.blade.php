<div class="container-fluid newest-container">
    <div class="container">
        <div class="row">
            <div class="header">
                <h3 class="three_learning bold">{{ trans('main.learning_styles') }}</h3>
                <br>
            </div>
            <div class="body body-s-r">
                <div id="three_learning_list">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4"><a href="/search?type=one" title="1 to 1 Class" class="content-box">
                                    <img src="{{baseURL()}}/bin/admin/Thumbnail/1_to_1.jpg">

                                    <div class="footer">
                                        <i class="fas fa-user violet"></i><span class="content-clock">1 to 1 Class</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a></div>

                            <div class="col-md-4">
                                <a href="/search?type=group" title="Group Course" class="content-box">
                                    <img src="{{baseURL()}}/bin/admin/Thumbnail/group_cour.jpg">

                                    <div class="footer">

                                        <i class="fas fa-users violet"></i><span class="content-clock">Group Course</span>
                                        <div class="clearfix"></div>

                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="/search?type=video" title="Video Course" class="content-box">
                                    <img src="{{baseURL()}}/bin/admin/Thumbnail/video_Cour.jpg">

                                    <div class="footer">
                                        <i class="fas fa-video violet"></i><span class="content-clock">Video Course</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container-fluid news-container contents_box">
    <div class="container">
        <div class="row">
            <div class="top-user-container">
                <h4 class="homeHeading">{{ trans('Meet our teachers') }}</h4>
                <div class="body body-s-r">
                    <span class="nav-right"></span>
                    <div class="teacher-carousel owl-carousel owl-theme">
                        @if(isset($user_rate))
                            @foreach($user_rate as $ur)

                                <?php
                                $userMetas = get_all_user_meta($ur->id);
                                ?>
                                <div class="owl-car-s">

                                            <div class="teacher-box">
                                        <?php echo getProfileBox($ur->id);?>
                                            <h4 class="bold">{{$ur->name}}</h4>
                                            <span>{{@$userMetas['meta_short_title']}}</span>

                                            <?php echo getTeacherRating($ur->rate_point);?>

                                </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid newest-container">
    <div class="container">
        <div class="row">
            <h4 class="homeHeading">Popular Courses</h4>
            <div class="body">

                <div class="course-carousel owl-carousel owl-theme">
                    @foreach($popular_content as $popular)
                        <?php $meta = arrayToList($popular->metas, 'option', 'value'); ?>
                        <div class="owl-car-s">
                            <a href="{!! baseURL() !!}/product/{{ $popular->id }}" title="{{ $popular->title }}"
                               class="course-box">
                                <div class="imageBox" style="overflow:hidden;width:100%;height:147px;border-radius:7px">
                                <img src="{!! baseURL() !!}{{ !empty($popular->image) ? $popular->image : '/bin/admin/images/no-video.png' }}"/>
                                </div>
                                    <h4 class="bold paz">{!! truncate($popular->title,25) !!}</h4>
                                <span class="teacherName">{{get_username($popular->user_id)}}</span>
                                <div class="teacher-rating">
                                    @for($i=0;$i<5;$i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid" style="margin-bottom:0px;background:#fff">
    <div class="row">
        <div class="parts_global"
             style="background-image:url('{!! baseURL() !!}/bin/admin/images/globe.jpg'); background-position: top center;background-size: 100%;background-repeat: no-repeat;">

            <div class="col-md-12 text-center" style="padding-top:18%;color:#444;padding-bottom:30px">
                <div style="margin:30px 0;">
                    <div style="font-size:28px;font-weight:900;margin:15px 0;">We connect the community with<br>Quran & Arabic</div>
                    <div style="font-size:16px;margin:15px 0;">Let's start the change, lets connect with MyMoon</div>

                    <a href="/search" class="btn btn-lg" style="background:#932680;color:#fff">START LEARNING NOW</a>
                </div>
            </div>
            <div class="container hm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="border-box">
                            <a href="#">
                                <img src="{!! baseURL() !!}/bin/admin/images/ic_budget@2x.png">
                                <span style="font-size:18px">Budget-friendly</span><br>
                                <p>Find affordable courses and tutors for your budget. </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-box">
                            <a href="#">
                                <img src="{!! baseURL() !!}/bin/admin/images/ic_safeguardded@2x.png">
                                <span style="font-size:18px">Safeguarded and secure</span><br>
                                <p>All teacher profiles are checked and verified to guarantee a safe learning
                                    environment for you
                                    and your kids.</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-box">
                            <a href="#">
                                <img src="{!! baseURL() !!}/bin/admin/images/ic_learn@2x.png">
                                <span style="font-size:18px">Learn anywhere, anytime</span><br>
                                <p>Fit lessons into your busy schedule with flexible booking system</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-box">
                            <a href="#">
                                <img src="{!! baseURL() !!}/bin/admin/images/ic_professional@2x.png">
                                <span style="font-size:18px">Professional and certified teachers</span><br>
                                <p>Easily find certified, professional and dedicated Quran and Arabic tutors</p>
                            </a>
                        </div>
                    </div>
                </div>
                <br><br>
                <h3 class="text-center">Quran and Arabic</h3>
                <br><br><br>
                <div class="row hmm">
                    <div class="col-md-4">
                        <div class="border-box">
                            <a href="#">
                                <div class="pink-box">
                                    <img src="{!! baseURL() !!}/bin/admin/images/ic_recite@2x.png">
                                </div>
                                <span style="font-size:18px">Recite the Quran accurately</span><br>
                                <p>Learn the rules of Tajweed and recite the words of Allah correctly.</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-box">
                            <a href="#">
                                <div class="pink-box">
                                    <img src="{!! baseURL() !!}/bin/admin/images/ic_understand@2x.png">
                                </div>

                                <span style="font-size:18px">Understand the Quran</span><br>
                                <p>Learn Arabic, the language of the Quran, and understand the message of your creator.</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-box">
                            <a href="#">
                                <div class="pink-box">
                                    <img src="{!! baseURL() !!}/bin/admin/images/ic_read@2x.png">
                                </div>
                                <span style="font-size:18px">Read and speak Arabic fluently</span><br>
                                <p>Gain the confidence to have flawless conversations in Arabic. </p>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid newest-container testi">
    <div class="container">
        <div class="row">
            <h3 style="margin:60px 0 10px 0;text-align:center;">What our students and teachers says</h3>
            <div class="body body-s-r">

                <div class="testimonial-carousel owl-carousel owl-theme">


                    @foreach($feedbacks as $feedback)


                    <div style="padding-top:20px">
                        <div class="teacher-box">
                                <img src="{{asset('/assets/admin/img/home-feedback/'.$feedback->serial.'.jpg')}}">

                                <p style="font-size:18px">{{$feedback->reviewer}}</p>
                                <p><strong>{{$feedback->title}}</strong></p>
                                <p>{{$feedback->review}}</p>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

<div class=""
     style="background-image:url('{!! baseURL() !!}/bin/admin/images/student.jpg'); background-position: top center;background-size: cover;background-repeat: no-repeat;padding:80px 0px">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ctaBox">
                    <h2 class="bold">Become a teacher and<br>benefit from MyMoon </h2>
                    <h4>Learn and teach anywhere, anytime</h4>
                    <a href="/registerTeacher" class="btn btn-lg btn-primary">START TEACHING</a>
                </div>
            </div>
        </div>
    </div>
</div>


