@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <style>
        #maincontent {
            background: #fff;
        }

        .aboutTop {
            background: url({{url('/assets/default/images/pages/group-2541.png')}}) no-repeat top center;
            background-size: cover;
            min-height: 678px;
            padding: 20px 30px;
        }

        .team, .aboutTop,.values,.partners {
            color: #000;
            font-size: 16px;
            text-align: center;
            margin: 40px 0;
        }

        .vision,.mission {
            color: #000;
            font-size: 16px;
            margin: 40px 0;
        }
        .vision h1,.mission h1{
            text-align:center;
        }
        #truncated {
            display: none;
        }

        .teamBox {
            padding: 10px;
            text-align: center;
            font-size: 14px;

        }

        .teamBox h4 {
            margin: 10px 0 5px 0;

        }

        .teamBox img {
            max-width: 120px;
        }

        .valueBox {
            padding: 10px;
            text-align: center;
        }

        .valueBox img {
            width: 100%;
            max-width: 280px;
        }
        @media only screen and (max-width: 620px) {
            .aboutTop h1{font-size:20px}
            .aboutTop {
                font-size:13px;
            }
            .team, .aboutTop,.values,.partners {
                margin: 20px 0;
            }
        }
    </style>
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="aboutTop">
                    <h1>About Us</h1>
                    <p>MyMoon is an online learning platform that aims to spread the knowledge of The Qur’an and Arabic all over the world. Founded by two inspired and motivated revert sisters, Lara Jennings and Andrea Amina, MyMoon is the dream project that became a reality with the help of Allah.
                        <span id="truncated">
                        The objective was to fill the gap that students and teachers felt, as it was difficult to find lessons in different languages that were both flexible and fair. The teachers at MyMoon come from all over the world and with different specializations, teaching courses from beginner-level to the highest Qur’an exam: the Ijazah. MyMoon is unique in the way that it allows both the teachers and the students to select their preferred language and teach and learn from the comfort of their homes, at their convenient timings, and in completely safe virtual classrooms. One of the unique features of MyMoon that we are fairly proud of is that we have three different learning methods that students can choose from, one-to-one lessons, group courses, or purchase a monthly plan where they can learn in an organized manner throughout the month. Through this platform, we wanted to provide the teachers a fair source of income and also connect underprivileged students with sponsors to help them gain beneficial knowledge. As an Ummah, we have a responsibility to promote the pure source of Islam, The Qur’an. Therefore, a particular part of our profit goes to charity and is used in further developing resources for learning.
                    </span>
                        <br><br><a id="showBtn" href="#" onclick="showMore()">Read More <i class="fas fa-angle-double-right"></i></a>
                    </p>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="vision">
                <h1>Vision</h1>
                <p>The vision of MyMoon is inspired by this one single hadeeth:<br>
                    <strong>“The best among you (Muslims) are those who learn the Qur'an and teach it.”</strong><br><br>
                    Learning and understanding the Qur’an is a fundamental part of Islam, and those who spread its lights are among the most noble of people. MyMoon’s vision is to make it possible for this light of the Qur’an to reach every home, every family, and every heart yearning for the radiance of guidance. Our values and ethics are based purely on the teachings of the Messenger of Allah (PBUH). Honesty, humility, friendliness, dedication, love for the deen, and hunger for spreading the knowledge of the Qur’an and Arabic are some of the values that drive the MyMoon team.
                </p>
                </div>
            </div>
        </div>

        <div class="h-20"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="mission">
                    <h1>Mission</h1>
                    <p>The mission of MyMoon is crystal clear. We believe that as an Ummah, we have a responsibility. We have been gifted with this beautiful Qur’an and it is our duty to spread its knowledge far and wide. One of the aspects of learning the Qur’an is learning the language in which it was revealed, Arabic. It is therefore our objective to help people learn Arabic so that they can understand and implement the teachings of the Qur’an in their lives.</p>
                    <p><strong>The mission of MyMoon is to:</strong></p>
                    <ul>
                        <li>Spread the light of the Qur’an among the Ummah.</li>
                        <li>Spread the knowledge of Arabic.</li>
                        <li>Bring the Ummah together through our platform.</li>
                        <li>Bring back barakah into the lives of people by making Qur’an a part of their busy schedule.</li>

                    </ul>
                    <p><strong>We aim to achieve this mission by providing:</strong></p>
                    <ul>
                        <li>Simplified online Qur’an and Arabic classes to both kids and adults.</li>
                        <li>Classes according to the convenience of the students.</li>
                        <li>A safe and friendly environment for the students that aids in their learning process.</li>
                        <li>Multilingual and diverse options for classes.</li>
                        <li>A variety of classes such as group courses and one- to- one lessons.</li>
                        <li>An easy and transparent paying system to avoid any confusion or complication.</li>
                        <li>A range of free resources and material to help the students’ progress.</li>
                    </ul>
                    <p>In essence, MyMoon is a platform to help those who are making a constant effort to take one step forward in deen.</p>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="team">
                    <h1>Meet the Team</h1>
                    <p>The MyMoon team is a diverse multi-lingual group of highly motivated and self-driven individuals who share a common vision.
                        Every member of the platform puts in 100% in their respective area, which is why we are where we are today, on the way to becoming one of the most user-friendly and effective Qur’an and Arabic learning platforms. Our team is passionate about what they do, and it shows! </p>
                    <br><br>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/5.png')}}">
                                <h4 class="bold">Andrea</h4>
                                <span>Co-founder</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/6.png')}}">
                                <h4 class="bold">Lara</h4>
                                <span>Co-founder</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/8.png')}}">
                                <h4 class="bold">Sheikha Hanaa</h4>
                                <span>Head teacher</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/4.png')}}">
                                <h4 class="bold">Laila</h4>
                                <span>Teacher-Coordinator</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/2.png')}}">
                                <h4 class="bold">Neda</h4>
                                <span>Copy-Writer</span>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/1.png')}}">
                                <h4 class="bold">Fatlum</h4>
                                <span>Designer</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/9.png')}}">
                                <h4 class="bold">Rocky</h4>
                                <span>Project Manager</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="teamBox">
                                <img src="{{url('/assets/default/images/pages/7.png')}}">
                                <h4 class="bold">Nazmul</h4>
                                <span>Senior Developer</span>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="values">
                    <h1>Our Values</h1>
                    <p>The MyMoon team is a diverse multi-lingual group of highly motivated and self-driven individuals who share a common vision. Every member of the platform puts in 100% in their respective area, which is why we are where we are today, on the way to becoming one of the most user-friendly and effective Qur’an and Arabic learning platforms. Our team is passionate about what they do, and it shows! </p><br><br>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/friendly.png')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/helpful.png')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/creative.png')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/hungry.png')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/happy.png')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="valueBox">
                                        <img src="{{url('/assets/default/images/pages/humble.png')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="partners">
                    <h1>Our Partners</h1>
                    <p>MyMoon is a community marketplace for learning and teaching, so our aim is to connect with other progressive and inspiring Muslim ventures and entrepreneurs. We strongly believe that the benefit of the Ummah is in unity and in supporting and helping each other. Working together with Muslim visionaries from all over the world in different productive events and projects is a part of MyMoon’s vision. Through our platform, we want to help promote the exceptional work and efforts of our partners and collectively make a difference. </p><br><br>
                    <div class="row">
                        <div class="col-md-3 text-center">
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="#"><img src="{{url('/assets/default/images/pages/ic_adam.png')}}">
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="#"><img src="{{url('/assets/default/images/pages/alihuda_logo_slogan_transparent.png')}}"></a>
                        </div>
                        <div class="col-md-3 text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                @endsection
                @section('script')
                    <script>
                        function showMore() {
                            $('#truncated').toggle();
                            $('#showBtn').hide();
                        }
                    </script>
@endsection
