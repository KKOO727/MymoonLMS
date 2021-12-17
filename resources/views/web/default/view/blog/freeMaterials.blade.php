@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <style>
        #maincontent {
            background: #fff;
        }

        .freeMaterial {
            background: url({{url('/assets/default/images/pages/free-material.png')}}) no-repeat top center;
            background-size: cover;
            min-height: 427px;
            padding: 20px 40px;
        }

        .freeMaterial {
            color: #000;
            font-size: 16px;
            margin: 40px 0;
        }

        .imageBox {
            width:100%;
            overflow:hidden;
            text-align:center;
        }
        .free-box{
            clear: both;
            display: block;
            margin: 10px 0;
            border: 1px solid #e1e1e1;
            min-height:471px;

        }
        .free-box h4 {
            color: #444;
            margin: 10px;
            font-size: 16px;
        }

        .free-box .teacherName {
            color: #000;
            font-size: 16px
        }

        .imageBox img {
            height: auto;
            width:100%;
        }

        .teacher-rating {
            margin:10px;
        }
    </style>
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="freeMaterial">
                    <h1>Free Material</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Salam and Welcome to our free material page. Here you’ll find books, flashcards, sheets, collections and much more! All about Arabic and Quran. You are free to share and forward everything you find on this page. If you have any feedback, suggestions or if you want to help with our free material, please contact us.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="materials">

                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/quranreading_stepbystep.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-1.jpg')}}">
                                </div>
                                <h4 class="bold paz">Quran Reading Step by Step</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/quran-lesen-schritt-für-schritt-fertig.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-2.jpg')}}">
                                </div>
                                <h4 class="bold paz">Quranlesen Schritt für Schritt (German version)</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/quranreading_french_complete.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-3.jpg')}}">
                                </div>
                                <h4 class="bold paz">Lire le Quran (French version)</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/mymoon-quran-reading-arabic.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-4.jpg')}}">
                                </div>
                                <h4 class="bold paz" style="direction: rtl">قرارة القران (Arabic Version)</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/mushaf_medinah.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-5.jpg')}}">
                                </div>
                                <h4 class="bold paz">Quran - Mushaf Medina - Arabic</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/start-with-Arabic-2.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-6.jpg')}}">
                                </div>
                                <h4 class="bold paz">Start with Arabic (Arabic Beginners book) free version</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/learn_istikhara.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-7.jpg')}}">
                                </div>
                                <h4 class="bold paz">Learn Istikhara</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/surahs-amma-flashcards-final.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-8.jpg')}}">
                                </div>
                                <h4 class="bold paz">Pick-n´-recite Flashcards - Juz Amma</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/prayer-flashcards.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-9.jpg')}}">
                                </div>
                                <h4 class="bold paz">Prayer Flashcards - English Version</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/prayer-flashcards-deutsch.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-10.jpg')}}">
                                </div>
                                <h4 class="bold paz">Gebets-Karten - German Version</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/prayer-flashcards-arabic.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-11.jpg')}}">
                                </div>
                                <h4 class="bold paz">Prayer Flashcards - Arabic Version</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/learn-to-pray-final.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-12.jpg')}}">
                                </div>
                                <h4 class="bold paz">Learn to Pray - English Version</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/ramadan-preperation-planner.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-13.jpg')}}">
                                </div>
                                <h4 class="bold paz">Ramadan Preparation Planner - English</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/takweer-flashcards.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-14.jpg')}}">
                                </div>
                                <h4 class="bold paz">Surah at-Takweer Flashcards</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/mymoon-1442-hijri-year-activity-book.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-15.jpg')}}">
                                </div>
                                <h4 class="bold paz">Hijri Year 1442 Activity Book</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{url('/assets/default/images/free/names_of_allah_1-25.pdf')}}" class="free-box">
                                <div class="imageBox" style="">
                                    <img src="{{url('/assets/default/images/free/free-16.jpg')}}">
                                </div>
                                <h4 class="bold paz">Allahs beautiful Names Part 1 (1-25)</h4>

                                <div class="teacher-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="h-20"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-30"></div>
@endsection
@section('script')

@endsection
