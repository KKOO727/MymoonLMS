
<div class="container-fluid newest-container">
    <div class="container">
        <div class="row">
            <div class="header">
                <span class="pull-left">{{ trans('main.most_sold_products') }}</span>
                <a href="{!! baseURL() !!}/category?order=sell" class="more-link pull-right">{{ trans('main.load_more') }}</a>
            </div>
            <div class="body body-s-r" dir="ltr">
                <span class="nav-right"></span>
                <div class="owl-carousel">
                    @foreach($sell_content as $new)
                        <?php $meta = arrayToList($new->metas, 'option', 'value'); ?>
                        <div class="owl-car-s" dir="rtl">
                            <a href="{!! baseURL() !!}/product/{{ $new->id }}" title="{{ $new->title }}" class="content-box">
                                <img src="{!! baseURL() !!}{{ !empty($meta['thumbnail']) ? $meta['thumbnail'] : '' }}"/>
                                <h3>{!! truncate($new->title,30) !!}</h3>
                                <div class="footer">
                                    @if(isset($new->user))
                                        <span class="avatar" title="{{ $new->user->name }}" onclick="window.location.href = '/profile/{{ $new->user->id }}'">
                                            <img src="{!! baseURL() !!}{{ get_user_meta($new->user_id,'avatar',get_option('default_user_avatar','')) }}">
                                        </span>
                                    @endif
                                    <label class="pull-right">{!! contentDuration($new->id) !!}</label>
                                    <span class="boxicon mdi mdi-clock pull-right"></span>
                                    <span class="boxicon mdi mdi-wallet pull-left"></span>
                                    <label class="pull-left">
                                        @if(isset($meta['price']) && $meta['price']>0)
                                            {{currencySign()}}{{ price($new->id,$new->category_id,$meta['price'])['price'] }}
                                        @else
                                            {{ trans('main.free') }}
                                        @endif
                                    </label>
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
