@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')

    <header class="sliderHeader">

        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{baseURL().'/bin/admin/web.mp4'}}" type="video/mp4">
        </video>
        <div class="slider-caption">

            <div class="slider-caption-overlay" style="position: relative;">
                <h3>{{ get_option('main_page_slide_title','') }}</h3>
                <br>
                <span>{{ get_option('main_page_slide_text','') }}</span>
                <br><br>
                <div class="row search-box">
                    <form action="/search">
                        {{ csrf_field() }}
                        <input type="text" name="q" class="col-md-11" placeholder="Search for a teacher"/>
                        <button type="submit" name="search" class="pull-left col-md-1"><span class="homeicon mdi mdi-magnify"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    @if(isset($setting['site']['main_page_newest_container']) and $setting['site']['main_page_newest_container'] == 1)
        @include(getTemplate() . '.view.parts.newest')
    @endif

    @if(isset($setting['site']['main_page_vip_container']) and $setting['site']['main_page_vip_container'] == 1)

    @endif
    @if(isset($setting['site']['main_live_class']) and $setting['site']['main_live_class'] == 1)

    @endif


@endsection
