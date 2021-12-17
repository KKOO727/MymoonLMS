@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <style>
        .support {
            font-size: 15px;

            background: #fff;
            padding: 20px;
        }
        .profile-book{
            font-size: 15px;
        }
        .support img {
            width: 100%
        }
    </style>
    <div class="h-30"></div>
    <div class="h-30"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="contact">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="support">
                                <img src="{{url('/assets/default/images/pages/support-thumb-2.png')}}">
                                <h3 class="bold">Help Make Quran Easily Accessible to 100,000 People Worldwide with MyMoon</h3>
                                <p>Bring the Quran to over 100,000 households by supporting our project of building an International Online platform for Arabic and Quran for everyone.</p>
                                <hr>
                                <h4 class="bold">Where is the Qur’an?</h4>
                                <p>Sadly, it doesn't fit in the schedule for most of us, and that's not always our fault.</p>
                                <p>Many factors make it difficult to find time to learn the Qur’an and Arabic and make it a part of our daily lives. Some of them are:</p><br>
                                <img src="{{url('/assets/default/images/pages/support-thumb-3.png')}}"><br><br>
                                <p>Especially for the kids of our generation and reverts, who need the guidance of Allah’s book to become strong, righteous Muslims. Our lives are consumed by so many distractions that we forget to give time to the Quran. </p>
                                <hr>
                                <h4 class="bold">Our Story:</h4>
                                <p>27% isn’t much! It's not even half. That's what I thought when I came to know that the money I was paying to my Online Quran teacher isn't reaching her in full. In fact, she's only receiving 27% of it from the institute.</p>
                                <p>This revelation started a chain of events that led to the formation of My Moon.</p>
                                <p> Assalam Alaikum. We are two revert German sisters, Lara Jennings and Andrea Amina, and we decided to form My Moon after facing difficulties in learning Arabic and the Qur’an.</p>
                                <p>We struggled to find convenient classes in our language, and often felt exhausted to the point of giving up. All praise is due to Allah, Who kept us steadfast and brought us to the next chapter of our lives and a revolutionising change.</p>
                                <p>Our relationship with the Qur’an is essential to bring goodness into our lives. </p>
                                <img src="{{url('/assets/default/images/pages/mission.png')}}"><br><br>
                                <h4 class="bold">Our Mission: from Struggles to Strength</h4>
                                <p>MyMoon was launched in November 2020 after a lot of planning. With the aim to provide simplified online Arabic and Quran Classes to not just Kids but also Adults who wish to learn conveniently from home. MyMoon is a unique multilingual and diverse online Platform.</p>
                                <p>With over 100 teachers and more than 700 students, we're all set and running.</p>
<ul>
    <li>Bringing classes to YOU, at your safe space</li>
    <li>Providing classes in the language YOU choose</li>
    <li>Giving the option to choose YOUR convenient time</li>
    <li>Providing feedback and safety checks for YOUR assurance</li>
                                    <li>Providing an simplified enrolment and payment method</li>
</ul>
                                <p>Our mission to spread the light of the Qur’an is taking shape beautifully.</p>
                                <p>The response has been tremendously encouraging.</p>
                                <p>The teachers love it, the students love it, and the parents love it.</p>
<hr>
                                <h4 class="bold">What have we done so far?</h4>
                                <p>Here’s what some of our students achieved through My Moon.
                                    With each new success story, our belief in the vision of My Moon grows stronger.</p>
                                <div class="review-list">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="profileImageBox" style="background:url({{url('/assets/default/images/pages/umm.png')}})">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h4 class="bold">Umm Sarah, UK</h4>
                                            My 7 years old daughter loves her MyMoon teacher. She is always looking forward to the lessons. She has already memorized 7 Surahs with him in only 2 months! Alhamdou li Allah!
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="profileImageBox" style="background:url({{url('/assets/default/images/pages/abubakr.png')}})">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h4 class="bold">Abubakar, Uganda</h4>
                                            I am so happy...! MyMoon gave me this fantastic opportunity to learn for free online to improve my Quran reading. The Sheikh I am learning with is so great. Down to earth and super encouraging. All thanks to Allah and then the MyMoon team for all their effort to make this happen.
                                        </div>
                                    </div>
                                    <hr>

                                </div>
                                <p>Here’s what some of our students achieved through My Moon.</p>
                                <p>With each new success story, our belief in the vision of My Moon grows stronger.</p>
                                <p> Help us spread it far and wide!</p>
                                <p>Help us in spreading the noor (light) of the Qur’an to every Muslim household. </p>

                                <img src="{{url('/assets/default/images/pages/support-thumb-4.png')}}"><br><br>
                                <p>We want to make My Moon a global platform and make it more efficient, user-friendly, and fast. We are in the process of improving the design, structure, and effectiveness of My Moon.</p>
                                <p>But all of this is possible if you’re onboard with us.</p>
                                <p>We need your support and help in reaching a goal of 15.000 $ as a Sadaqa Jariya for all of you!</p>
                                <p>Every single penny will be used for the khair of the ummah, and when the intentions are for the sake of Allah even a penny is worth a lot!</p>
                                <p>Everyone has the right to be guided in the light of the Qur’an.</p>
                                <br>
                                <h4 class="bold">Will you be one of the torch bearers?</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course_details profile-book">
                                <h4 class="bold">Will you be one of the torch bearers?</h4>
                                <p>Bring the Quran to over 100,000 households by supporting our project of building an International Online platform for Arabic and Quran for everyone.</p>
<p>Help us in spreading the noor (light) of the Qur’an to every Muslim household.</p>
                                <br><br>
                                <a href="#" class="btn btn-primary btn-block">Support Us</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
    </div>
    <div class="h-30"></div>
@endsection
@section('script')

@endsection
