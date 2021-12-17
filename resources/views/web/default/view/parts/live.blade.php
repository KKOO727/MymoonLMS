
<div class="container-fluid newest-container">
    <div class="container">
        <div class="row">
            <div class="header">
                <span class="popular pull-left header-s">{{ trans('main.live_content') }}</span>
                <a href="{!! baseURL() !!}/category?course=webinar" class="pull-right more-link">{{ trans('main.load_more') }}</a>
            </div>
            <div class="body body-s-r">
                <span class="nav-right"></span>
                <div class="owl-carousel">
                    @foreach($live_content as $popular)
                        <?php $meta = arrayToList($popular->metas, 'option', 'value'); ?>
                        <div class="owl-car-s" dir="rtl">
                            <a href="{!! baseURL() !!}/product/{{ $popular->id }}" title="{{ $popular->title }}" class="content-box">
                                <img src="{!! baseURL() !!}{{ !empty($meta['thumbnail']) ? $meta['thumbnail'] : '' }}"/>
                                <h3>{!! truncate($popular->title,35) !!}</h3>
                                <div class="footer">
                                    @if(isset($popular->user))
                                    <span class="avatar" title="{{ $popular->user->name }}" onclick="window.location.href = '/profile/{{ $popular->user->id }}'"><img src="{{ get_user_meta($popular->user_id,'avatar',get_option('default_user_avatar','')) }}"></span>
                                    @endif
                                        <label class="pull-right content-clock">{!! contentDuration($popular->id) !!}</label>
                                    <span class="boxicon mdi mdi-clock pull-right"></span>
                                    <span class="boxicon mdi mdi-wallet pull-left"></span>
                                    <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {{currencySign()}}{{ price($popular->id,$popular->category_id,$meta['price'])['price'] }} @else {{ trans('main.free') }} @endif</label>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <span class="nav-left pull-right"></span>
            </div>
        </div>
    </div>
</div>
