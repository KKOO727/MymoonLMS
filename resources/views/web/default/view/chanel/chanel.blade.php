@extends(getTemplate().'.view.layout.layout')
@section('title')
    {!! $setting['site']['site_title'].'Channel- '.$chanel->title !!}
@endsection
@section('page')

<?php $base_url= baseURL(); ?>
    <div class="container-fluid profile-top-background" style="background: url('<?=$base_url?>{{ $chanel->image ?? '' }}');">
        <div class="col-md-3 col-xs-12">

        </div>
        <div class="col-md-9 col-xs-12 bottom-section">
            <span>
                <label class="profile-name">{{ $chanel->title }}</label>
            @if($follow == 0)
                    <a class="btn btn-red btn-hover-animate" href="<?=$base_url?>/chanel/follow/{{ $chanel->user_id }}"><span class="homeicon mdi mdi-plus"></span> {{ trans('main.follow') }}</a>
                @else
                    <a class="btn btn-red btn-hover-animate" href="<?=$base_url?>/chanel/unfollow/{{ $chanel->user_id }}"><span class="homeicon mdi mdi-close"></span>{{ trans('main.unfollow') }}</a>
                @endif
                <label class="buttons"><span class="homeicon mdi mdi-account-heart"></span><p>{{ !empty($follow) ? $follow : '0' }} {{ trans('main.followers') }}</p></label>
                <label class="buttons"><span class="homeicon mdi mdi-library-video"></span><p>{{ !empty($chanel->contents_count) ? $chanel->contents_count : 0 }} {{ trans('main.courses') }}</p></label>
                <label class="buttons"><span class="homeicon mdi mdi-clock"></span><p class="duration-f">{{ !empty($duration) ? $duration : '0' }}&nbsp;{{ trans('main.minutes_stat') }}</p></label>
        </div>
    </div>

    <div class="container-fluid profile-middle-background">
        <div class="container">
            <div class="col-md-2 col-xs-12 tab-con">
                @if($chanel->formal == 'ok')
                    <label title="Formal" class="formal-chanel"><i class="mdi mdi-check-circle"></i></label>
                @endif
                <img class="sbox3" src="<?=$base_url?>{{ $chanel->avatar }}"/>
                <div class="rate-section raty"></div>
            </div>
            <div class="location-section col-md-10 col-xs-12">
                <div><b>{!! $chanel->description !!}</b></div>
                <div><b><a href="<?=url('/') . '/' . Request::path();?>" class="uname-f">{{ $chanel->username }}</a></b></div>
            </div>
        </div>
    </div>

    <div class="h-10"></div>

    <div class="container-fluid cont-box-bg">

        <div class="container remove-padding-xs">

            <div class="col-xs-12">

                <div class="h-20"></div>
                <div class="h-10"></div>

                <div class="profile-section-fade newest-container remove-padding-xs remove-bg-xs newest-container-r">
                    <div class="body body-target-s">
                        <div class="row">
                            @foreach($chanel->contents as $vid)
                                @if($vid->content != null)
                                    @php $meta = arrayToList($vid->content->metas,'option','value'); @endphp
                                    <div class="col-md-3 col-sm-6 col-xs-6 tab-con">
                                        <a href="/product/{{ $vid->content->id }}" title="{{ $vid->content->title }}" class="content-box">
                                            <img src="{{ !empty($meta['thumbnail']) ? $meta['thumbnail'] : '' }}"/>
                                            <h3>{!! truncate($vid->content->title,35) !!}</h3>
                                            <div class="footer">
                                                <label class="pull-right">{{ !empty($meta['duration']) ? $meta['duration'] : '' }} {{ trans('main.min') }}</label>
                                                <span class="boxicon mdi mdi-clock pull-right"></span>
                                                <span class="boxicon mdi mdi-wallet pull-left"></span>
                                                <label class="pull-left">{{ currencySign() }}{{ !empty($meta['price']) ? $meta['price'] : '0' }}</label>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.raty').raty({starType: 'i'});
    </script>
@endsection
