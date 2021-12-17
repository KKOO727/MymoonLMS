@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    - {{ $product->title }}
@endsection
@section('page')
    <div class="container-fluid">
        <div class="row product-header">
            <div class="container">
                <div class="col-xs-12 col-md-8 tab-con">
                    <h2>{{ $product->title }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="h-20"></div>
    @if(!empty($product['discount']))
        <div class="container">
            <div class="col-xs-12 col-md-12">
                <div class="product-discount-container">
                    <div class="col-md-4 col-xs-12 tab-con">
                        <div class="container-s-r">
                            <strong class="red-r">@if($product->discount->last_date - time() > 86400) {{ (floor(($product->discount->last_date - time()) / (60 * 60 * 24))) }} @else 0 @endif</strong>
                            <strong>{{ trans('main.days') }}</strong>
                            <strong>{{ trans('main.special_offer') }}</strong>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-12 tab-con">
                        <div class="row">
                            <span class="off-btn">
                                <label>%{{ !empty($product->discount->off) ? $product->discount->off : 0 }}</label>
                                <label>{{ trans('main.discount') }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 tab-con">
                        <div class="countdown" dir="ltr">
                            <div class="bloc-time hours" data-init-value="{{ 24-date('H',time()) }}">
                                <div class="figure hours hours-1">
                                    <span class="top">2</span>
                                    <span class="top-back">
                                        <span>2</span>
                                    </span>
                                    <span class="bottom">2</span>
                                    <span class="bottom-back">
                                        <span>2</span>
                                    </span>
                                </div>
                                <div class="figure hours hours-2">
                                    <span class="top">4</span>
                                    <span class="top-back">
                                        <span>4</span>
                                    </span>
                                    <span class="bottom">4</span>
                                    <span class="bottom-back">
                                        <span>4</span>
                                    </span>
                                </div>
                            </div>
                            <div class="bloc-time min" data-init-value="{{ 60-date('m',time()) }}">
                                <div class="figure min min-1">
                                    <span class="top"></span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                                <div class="figure min min-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                            <div class="bloc-time sec" data-init-value="{{ 60-date('s',time()) }}">
                                <div class="figure sec sec-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                                <div class="figure sec sec-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
    @elseif(!empty($product->category->discount))
        <div class="container">
            <div class="col-xs-12 col-md-12">
                <div class="product-discount-container">
                    <div class="col-md-4 col-xs-12">
                        <div class="container-s-r">
                            <strong class="red-r">@if($product->category->discount->last_date - time() > 86400) {{ (floor(($product->category->discount->last_date - time()) / (60 * 60 * 24)))+1 }} @else 0 @endif Day</strong>
                            <strong>{{ trans('main.days') }}</strong>
                            <strong>{{ trans('main.special_offer') }}</strong>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <div class="row">
                            <span class="off-btn">
                                <label>%{{ !empty($product->category->discount->off) ? $product->category->discount->off : 0 }}</label>
                                <label>{{ trans('main.discount') }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="countdown" dir="ltr">
                            <div class="bloc-time hours" data-init-value="{{ 24-date('H',time()) }}">
                                <div class="figure hours hours-1">
                                    <span class="top">2</span>
                                    <span class="top-back">
                                        <span>2</span>
                                    </span>
                                    <span class="bottom">2</span>
                                    <span class="bottom-back">
                                        <span>2</span>
                                    </span>
                                </div>
                                <div class="figure hours hours-2">
                                    <span class="top">4</span>
                                    <span class="top-back">
                                        <span>4</span>
                                    </span>
                                    <span class="bottom">4</span>
                                    <span class="bottom-back">
                                        <span>4</span>
                                    </span>
                                </div>
                            </div>
                            <div class="bloc-time min" data-init-value="{{ 60-date('m',time()) }}">
                                <div class="figure min min-1">
                                    <span class="top"></span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                                <div class="figure min min-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                            <div class="bloc-time sec" data-init-value="{{ 60-date('s',time()) }}">
                                <div class="figure sec sec-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                                <div class="figure sec sec-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
    @endif
    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">
                <div class="col-md-4 col-xs-12 course_details">
                    <div class="product-details-box">
                        <span class="proicon mdi mdi-apps"></span>
                        <span class="pn-category">{{ $product->category->title }}</span>
                    </div>

                    <div class="product-details-box">
                        <span class="proicon mdi mdi-alarm"></span>
                        <span>{{ convertToHoursMins($Duration,'%01d hour %02d min') }}</span>
                    </div>
                    <div class="product-details-box">
                        <span class="proicon mdi mdi-database"></span><span>
                            {{ $MB }}
                            {{ trans('main.mb') }}
                        </span>
                    </div>
                    <div class="product-price-box">
                        <span class="proicon mdi mdi-wallet"></span>

                            <span id="buy-price">{{ currencySign() }}{{$product->price}}</span>

                    </div>
                    <div class="h-10"></div>
                    @if (isset($meta['price']))
                        <div class="product-buy-selection">
                            <form>
                                {{ csrf_field() }}
                                @if(isset($user) && $product->user_id == $user['id'])
                                    <a class="btn btn-orange product-btn-buy sbox3" id="buy-btn" href="/user/content/edit/{{ $product->id }}">{{ trans('main.edit_course') }}</a>
                                    <a class="btn btn-blue product-btn-buy sbox3" id="buy-btn" href="/user/content/part/list/{{ $product->id }}">{{ trans('main.add_video') }}</a>
                                @elseif(!$buy)
                                    @if(!empty($product->price) and $product->price != 0)
                                        <div class="radio">
                                            <input type="radio" id="radio-2" name="buy_mode" data-mode="download" value="{{ price($product->id,$product->category_id,$meta['price'])['price'] }}" checked>
                                            <label class="radio-label" for="radio-2">{{ trans('main.purchase_download') }}</label>
                                        </div>
                                    @endif

                                    @if($product->post == 1 && userMeta($product->user_id,'trip_mode') == 0)
                                        @if(!empty($product->price) and $product->price != 0)
                                            <div class="radio">
                                                <input type="radio" id="radio-1" data-mode="post" value="{{ price($product->id,$product->category_id,$meta['post_price'])['price'] }}" name="buy_mode">
                                                <label class="radio-label" for="radio-1">{{ trans('main.postal_purchase') }}</label>
                                            </div>
                                        @endif
                                    @endif

                                    @if(!empty($product->price) and $product->price != 0)
                                        <a class="btn btn-orange product-btn-buy sbox3" id="buy-btn" data-toggle="modal" data-target="#buyModal" href="">{{ trans('main.pay') }}</a>
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
                    @if(userMeta($product->user_id,'trip_mode') == 1 && userMeta($product->user_id,'trip_mode_date') > 0)
                        <div class="h-20"></div>
                        <div class="trip_mode_alert">
                            <span class="mdi mdi-shield-airplane"></span>
                            <span> {{ trans('main.vendor_vac') }}
                                {{ date('Y-m-d', userMeta($product->user_id,'trip_mode_date')) }}
                                {{ trans('main.vendor_vac_2') }} </span>
                        </div>
                    @endif
                </div>
                <div id="buyModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{ trans('main.purchase') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('main.select_payment_method') }}</p>
                                <p>
                                    <input type="hidden" id="buy_method" value="download">
                                <div class="radio">
                                    <input type="radio" class="buy-mode" id="mode-1" value="credit" name="buyMode">
                                    &nbsp;
                                    <label class="radio-label" for="mode-1">{{ trans('main.account_charge') }}&nbsp;<b id="credit-remain-modal">({{ currencySign() }}{{ $user['credit'] }})</b></label>
                                </div>
                                @if(get_option('gateway_paypal') == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-2" value="paypal" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-2"> Paypal </label>
                                    </div>
                                @endif
                                @if(get_option('gateway_paystack',0) == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-3" value="paystack" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-3"> Paystack </label>
                                    </div>
                                @endif
                                @if(get_option('gateway_paytm') == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-4" value="paytm" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-4"> Paytm </label>
                                    </div>
                                @endif
                                @if(get_option('gateway_payu') == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-5" value="payu" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-5"> Payu </label>
                                    </div>
                                @endif
                                @if(get_option('gateway_razorpay') == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-6" value="razorpay" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-6"> Razorpay </label>
                                    </div>
                                @endif
                                @if(get_option('gateway_cinetpay') == 1)
                                    <div class="radio">
                                        <input type="radio" class="buy-mode" id="mode-6" value="cinetpay" name="buyMode">
                                        &nbsp;
                                        <label class="radio-label" for="mode-6"> Cinetpay </label>
                                    </div>
                                @endif
                                <div class="h-10"></div>
                                <div class="table-responsive table-base-price">
                                    <table class="table table-hover table-factor-modal">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('main.amount') }}</th>
                                            <th class="text-center">{{ trans('main.discount') }}</th>
                                            <th class="text-center">{{ trans('main.tax') }}</th>
                                            <th class="text-center">{{ trans('main.total_amount') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{ $meta['price']}}</td>
                                            @if(isset($meta['price']) && $meta['price'] > 0 && price($product->id, $product->category->id, $meta['price']) > 0)
                                                <td class="text-center">{{ round((($meta['price'] - price($product->id, $product->category->id, $meta['price'])['price']) * 100) / $meta['price']) }}</td>
                                            @endif
                                            <td class="text-center">0</td>
                                            <td class="text-center">{{ price($product->id,$product->category->id,$meta['price'])['price'] }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive table-post-price table-post-price-s">
                                    <table class="table table-hover table-factor-modal" style="margin-bottom: 0;padding-bottom: 0;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('main.amount') }}</th>
                                            <th class="text-center">{{ trans('main.discount') }}</th>
                                            <th class="text-center">{{ trans('main.tax') }}</th>
                                            <th class="text-center">{{ trans('main.total_amount') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{ $meta['post_price'] }}</td>
                                            @if(isset($meta['post_price']) && $meta['post_price']>0)
                                                <td class="text-center">{{ round((($meta['post_price'] - price($product->id,$product->category->id,$meta['post_price'])['price']) * 100) / $meta['post_price']) }}</td>
                                                <td class="text-center">۰</td>
                                                <td class="text-center">۰</td>
                                                <td class="text-center">{{ price($product->id,$product->category->id,$meta['post_price'])['price'] }}</td>
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>
                            <div class="modal-body">
                                <div id="postAddressText">

                                    @if(isset($user))
                                        <p><b>{{ trans('main.address') }}</b>{!!  userAddress($user['id']) !!}</p>
                                    @endif
                                </div>
                                <div id="postAddress">
                                    <form method="post" class="form-horizontal" action="/user/profile/meta/store">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="control-label col-md-1 tab-con">{{ trans('main.province') }}</label>
                                            <div class="col-md-5 tab-con">
                                                <input type="text" class="form-control" name="state" value="{!! $userMeta['state'] ?? '' !!}"/>
                                            </div>
                                            <label class="control-label col-md-1 tab-con">{{ trans('main.city') }}</label>
                                            <div class="col-md-5 tab-con">
                                                <input type="text" name="city" value="{{ $userMeta['city'] ?? '' }}" class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-1 tab-con">{{ trans('main.address') }}</label>
                                            <div class="col-md-5 tab-con">
                                                <textarea name="address" rows="4" class="form-control">{{ $userMeta['address'] ?? '' }}</textarea>
                                            </div>
                                            <label class="control-label col-md-1 tab-con">{{ trans('main.zip_code') }}</label>
                                            <div class="col-md-5 tab-con">
                                                <input type="text" name="postalcode" value="{{ $userMeta['postalcode'] ?? '' }}" class="form-control text-center">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="submit" name="submit" class="btn btn-orange pull-left" value="Save">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="giftCard">
                                    <form method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="col-md-9 tab-con">
                                                <input type="text" dir="ltr" class="form-control text-center" placeholder="Discount or Gift code" name="gift-card" id="gift-card">
                                            </div>
                                            <div class="col-md-3 tab-con">
                                                <button type="button" name="gift-card-check" id="gift-card-check" class="btn btn-custom pull-left">{{ trans('main.validate') }}</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 text-center" id="gift-card-result"></div>
                                        </div>
                                    </form>
                                </div>
                                @if(isset($user))
                                    <div id="modal-user-category">
                                        <span>{{ trans('main.you_are_in') }}</span>
                                        <b>{{ $user['category']['title'] }}</b>
                                        <span>{{ trans('main.group_and') }}</span>
                                        <b>{{ $user['category']['off'] }}٪</b>
                                        <span> {{ trans('main.extra_discount') }}</span>
                                    </div>
                                @endif
                            </div>
                            @if(checkSubscribeSell($product))
                                <div class="modal-body">
                                    <h6 style="font-weight:bold;">You Can Subscribe..... Select Items</h6>
                                    <div class="h-10"></div>
                                    @if($product->price_3 > 0)<a href="/product/subscribe/{!! $product->id !!}/3/credit" p-id="{!! $product->id !!}" s-type="3" class="btn-subscribe btn btn-custom">3 month : {!! currencySign() !!}{!! $product->price_3 !!}</a>@endif
                                    @if($product->price_6 > 0)<a href="/product/subscribe/{!! $product->id !!}/6/credit" p-id="{!! $product->id !!}" s-type="6" class="btn-subscribe btn btn-custom">6 month : {!! currencySign() !!}{!! $product->price_6 !!}</a>@endif
                                    @if($product->price_9 > 0)<a href="/product/subscribe/{!! $product->id !!}/9/credit" p-id="{!! $product->id !!}" s-type="9" class="btn-subscribe btn btn-custom">9 month : {!! currencySign() !!}{!! $product->price_9 !!}</a>@endif
                                    @if($product->price_12 > 0)<a href="/product/subscribe/{!! $product->id !!}/12/credit" p-id="{!! $product->id !!}" s-type="12" class="btn-subscribe btn btn-custom">12 month : {!! currencySign() !!}{!! $product->price_12 !!}</a>@endif
                                </div>
                            @endif
                            <div class="modal-footer">
                                <button type="button" class="btn btn-custom pull-left" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                                <a href="javascript:void(0);" class="btn btn-custom pull-left" id="buyBtn">{{ trans('main.purchase') }}</a>
                                <a href="javascript:void(0);" class="btn btn-custom pull-right" id="btn-address" onclick="$('#postAddress').slideToggle(200);">{{ trans('main.change_address') }}</a>
                                <a href="javascript:void(0);" class="btn btn-custom pull-right" onclick="$('#giftCard').slideToggle(200);">{{ trans('main.have_giftcard') }}</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-8 col-xs-12 video-details">
                    @if(isset($part) && $part->server == 'youtube')
                        <iframe width="100%" height="435" src="{{ !empty($partVideo) ? $partVideo : $meta['video'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @elseif(isset($part) && $part->server == 'vimeo')
                        <iframe src="{{ !empty($partVideo) ? $partVideo : $meta['video'] }}" width="100%" height="435" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    @else
                        <video id="myDiv" controls>
                            <source src="{{ !empty($partVideo) ? $partVideo : $meta['video'] }}" type="video/mp4"/>
                        </video>
                    @endif
                    <div class="video-details-section">
                        @if(count($product->favorite) > 0)
                            <a title="Remove from favorites" href="/product/unfav/{{ $product->id }}">
                                <span class="playericon mdi mdi-star-off"></span>
                            </a>
                        @else
                            <a title="Add to favorites" href="/product/fav/{{ $product->id }}">
                                <span class="playericon mdi mdi-star"></span>
                            </a>
                        @endif
                        <a href="" title="Share">
                            <span class="playericon mdi mdi-share-variant"></span>
                        </a>
                        <a href="javascript:void(0);" class="course-id-s" title="Course Id.">
                            <span class="playericon mdi mdi-library-video"></span>
                            vt-{{ $product->id }}
                        </a>
                        <a class="pull-left views-s" title="Views" href="javascript:void(0)">
                            <span>{{ $product->view }}</span>
                            <span class="playericon mdi mdi-eye"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-20"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="col-md-4 col-xs-12">
                    <div class="row">
                        @if($live)
                            @foreach($live as $l)
                                <div class="course_details">
                                    <div class="product-user-box text-left">
                                        <h6 class="text-left">
                                            <span style="text-align: left !important;">{!! $l->title ?? '' !!}</span>
                                        </h6>
                                        <div class="h-10"></div>
                                        <span style="text-align: left !important;margin: 0;">
                                            <i class="proicon mdi mdi-calendar"></i>
                                            {!! $l->date !!}&nbsp;{!! $l->time ?? '' !!}
                                        </span>
                                        <span style="text-align: left !important;margin: 0;">
                                            <i class="proicon mdi mdi-clock"></i>
                                            {!! $l->duration !!}&nbsp;{!! trans('admin.minutes') !!}
                                        </span>
                                        @if($l->password != null && $l->password != '')
                                            <span style="text-align: left !important;margin: 0;">
                                                <i class="proicon mdi mdi-key"></i>
                                                {!! $l->password !!}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="product-user-box-footer">
                                        <a href="{!! $l->join_url ?? '' !!}" target="_blank">{{ trans('main.enter_live_class') }}</a>
                                    </div>
                                    <div class="h-25"></div>
                                </div>
                                <div class="h-10"></div>
                            @endforeach
                        @endif
                        <div class="course_details">
                            <div class="product-user-box">
                                <?php $userMetas = arrayToList($product->user->usermetas, 'option', 'value'); ?>
                                <img class="img-box" src="{{ !empty($userMetas['avatar']) ? $userMetas['avatar'] : get_option('default_user_avatar','') }}" class="img-responsive"/>
                                <h3>
                                    <a href="/profile/{{ $product->user->id }}"><span>{{ $product->user->name }}</span></a>
                                </h3>
                                <div class="user-description-box">
                                    {{ $userMetas['short_biography'] }}
                                </div>
                                <div class="text-center">
                                    @foreach($rates as $rate)
                                        @if (!empty($rate['image']))
                                            <img class="img-icon img-icon-s" src="{{ $rate['image'] }}" title="{{ $rate['description'] }}"/>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="product-user-box-footer">
                                <a href="/profile/{{ $product->user->id }}">{{ trans('main.vendor_profile') }}</a>
                            </div>
                            <div class="h-25"></div>
                        </div>
                    </div>
                    <div class="row">
                        @if(isset($ads))
                            @foreach($ads as $ad)
                                @if($ad->position == 'product-page')
                                    <a href="{{ $ad->url }}"><img src="{{ $ad->image }}" class="{{ $ad->size }}" id="ppage-s"></a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-8 col-xs-12 product-part-container">
                    <div class="user-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">{{ trans('main.course_content') }}</a></li>
                            @if($product->type == 'webinar' || $product->type == 'course+webinar')
                                <li><a href="#tab6" role="tab" data-toggle="tab">{{ trans('main.meeting') }}</a></li>
                            @Endif
                            <li><a href="#tab2" role="tab" data-toggle="tab">{{ trans('main.details') }}</a></li>
                            <li><a href="#tab3" role="tab" data-toggle="tab">{{ trans('main.prerequisites') }}</a></li>
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
                                                <a @if($product->content_type == 'captivate') href="/product/captivate/{{ $product->id }}/{{ $part['id'] }}" target="_blank" @else href="/product/part/{{ $product->id }}/{{ $part['id'] }}" @endif>
                                                    <div class="col-md-1 hidden-xs tab-con">
                                                        @if($buy or $part['free'] == 1)
                                                            <span class="playicon mdi mdi-play-circle"></span>
                                                        @else
                                                            <span class="playicon mdi mdi-lock"></span>
                                                        @endif
                                                    </div>
                                                    <div class="@if($product->download == 1) col-md-4 @else col-md-5 @endif col-xs-10 tab-con">
                                                        <label>{{ $part['title'] }}</label>
                                                    </div>
                                                </a>
                                                <div class="col-md-2 tab-con">
                                                    <span class="btn btn-gray btn-description hidden-xs" data-toggle="modal" href="#description-{{ $part['id'] }}">{{ trans('main.description') }}</span>
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
                                                <div class="col-md-2 text-center hidden-xs tab-con">
                                                    <span>{{ $part['size'] }} {{ trans('main.mb') }}</span>
                                                </div>
                                                <div class="col-md-2 hidden-xs tab-con">
                                                    <span>{{ $part['duration'] }} {{ trans('main.minute') }}</span>
                                                </div>
                                                @if($product->download == 1 && $product->content_type != 'captivate')
                                                    <div class="col-md-1 col-xs-2 tab-con">
                                                        <span class="download-part" data-href="/video/download/{{ $part['id'] }}"><span class="mdi mdi-arrow-down-bold"></span></span>
                                                    </div>
                                                @endif
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
                            <div class="tab-pane fade in tab-body" id="tab3">
                                @foreach($precourse as $pc)
                                    <?php $pmeta = arrayToList($pc->metas, 'option', 'value'); ?>
                                    <div class="col-md-4 col-xs-12 tab-con">
                                        <a href="/product/{{ $pc->id }}" title="{{ $pc->title }}" class="content-box content-box-r">
                                            <img src="{{ $pmeta['thumbnail'] }}"/>
                                            <h3>{!! truncate($pc->title,25) !!}</h3>
                                            <div class="footer">
                                                <span class="boxicon mdi mdi-wallet pull-left"></span>
                                                <label class="pull-left">{{ currencySign() }}{{ price($pc->id,$pc->category_id,$pmeta['price'])['price'] }}</label>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
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
                                                $start=strtotime($meeting->date.' '.$meeting->time);
                                                $end = strtotime('+'.$meeting->duration.' minutes', $start);
                                                $nowtime = strtotime(date('Y-m-d H:i:s', time()));

                                                //echo date('Y-m-d H:i:s',$start).'<br>';
                                                //echo date('Y-m-d H:i:s',$end).'<br>';
                                                //echo date('Y-m-d H:i:s',$nowtime).'<br>';

                                                if ($nowtime >= $start && $nowtime < $end) {
                                                    $meeting_link=$meeting->id.'-'.$meeting->content_id.'-'.$meeting->user_id.'-'.$start;
                                                    echo '<a href="#" class="btn btn-danger" onclick="startMeeting('.$meeting_link.')">Join Class</a>';
                                                }

                                                ?>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div id="meet"></div>
                            </div>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <div class="user-tabs">
                        <ul class="nav nav-tabs back-blue" role="tablist">
                            <li class="active"><a href="#vtab1" role="tab" data-toggle="tab">{{ trans('main.similar_courses') }}</a></li>
                            <li><a href="#vtab2" role="tab" data-toggle="tab">{{ trans('main.vendor_courses') }}</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in tab-body" id="vtab1">
                                @foreach($related as $rel)
                                    <?php $rmeta = arrayToList($rel->metas, 'option', 'value'); ?>
                                    <div class="col-md-4 col-xs-12 tab-con">
                                        <a href="/product/{{ $rel->id }}" title="{{ $rel->title }}" class="content-box content-box-r">
                                            <img src="{{ $rmeta['thumbnail'] }}"/>
                                            <h3>{!! truncate($rel->title,25) !!}</h3>
                                            <div class="footer">
                                                <span class="boxicon mdi mdi-wallet pull-left"></span>
                                                <label class="pull-left">{{ currencySign() }}{{ price($rel->id,$rel->category_id,$rmeta['price'])['price'] }}</label>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-pane fade tab-body" id="vtab2">
                                @foreach($product->user->contents as $puc)
                                    @if($puc->id != $product->id)
                                        <?php $umeta = arrayToList($puc->metas, 'option', 'value'); ?>
                                        <div class="col-md-4 col-xs-12 tab-con">
                                            <a href="/product/{{ $puc->id }}" title="{{ $puc->title }}" class="content-box content-box-r">
                                                <img src="{{ $umeta['thumbnail'] }}"/>
                                                <h3>{!! truncate($puc->title,25) !!}</h3>
                                                <div class="footer">
                                                    <span class="boxicon mdi mdi-wallet pull-left"></span>
                                                    <label class="pull-left">{{ currencySign() }}{{ price($puc->id,$puc->category_id,$umeta['price'])['price'] }}</label>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="h-20" id="blog-comment-scroll"></div>
                    <div class="user-tabs">
                        <ul class="nav nav-tabs back-green" role="tablist">
                            <li class="active"><a href="#ctab1" role="tab" data-toggle="tab">{{ trans('main.comments') }}&nbsp;({{ $product->comments_count }})</a></li>
                            @if($product->support == 1)
                                @if(!empty($product->supports->sum('rate')) and $product->supports->sum('rate') > 0 and !empty($product->supports) and count($product->supports) > 0)
                                    <li><a href="#ctab2" role="tab" data-toggle="tab">Support &nbsp;(Rating: {{ $product->support_rate }})</a></li>
                                @else
                                    <li><a href="#ctab2" role="tab" data-toggle="tab">{{ trans('main.support') }}</a></li>
                                @endif
                            @endif
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in blog-comment-section body-target-s" id="ctab1">
                                @if(isset($user))
                                    <form method="post" action="/product/comment/store/{{ $product->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="content_id" value="{{ $product->id }}"/>
                                        <input type="hidden" name="parent" value="0"/>
                                        <div class="form-group">
                                            <label>{{ trans('main.your_comment') }}</label>
                                            <textarea class="form-control" name="comment" rows="4" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-custom" value="Send">
                                        </div>
                                    </form>
                                @else
                                    <div class="col-xs-12 text-center support-lock support-lock-s">
                                        <span>{{ trans('main.login_to_comment') }}</span>
                                        <br>
                                        <span class="mdi mdi-lock"></span>
                                    </div>
                                @endif
                                <ul class="comment-box support-list1">
                                    @foreach($product->comments as $comment)
                                        @if($comment->parent == 0)
                                            <?php $userMeta = arrayToList($comment->user->usermetas, 'option', 'value'); ?>
                                            <li class="user-metas">
                                                <img src="{{ $userMeta['avatar'] ?? '/assets/default/images/user.png' }}" alt=""/>
                                                <a href="/profile/{{ $comment->user_id }}">{{ $comment->name }} @if($comment->user->buys_count > 0) <b class="green-s">({{ trans('main.student') }})</b> @elseif($comment->user->contents_count > 0) <b class="blue-s">({{ trans('main.vendor') }})</b> @else  <b class="gray-s">({{ trans('main.user') }})</b> @endif</a>
                                                <label class="pull-left">{{ date('d F Y | H:i',$comment->created_at) }}</label>
                                                <span>{!! $comment->comment !!}</span>
                                                @if($buy or (isset($user) and $product->user_id == $user['id']))<span><a href="javascript:void(0);" answer-id="{{ $comment->id }}" answer-title="{{ $comment->name }}" class="pull-left answer-btn">{{ trans('main.reply') }}</a> </span>@endif
                                            </li>
                                            @if(count($comment->childs) > 0)
                                                <ul class="col-md-11 col-md-offset-1 answer-comment">
                                                    @foreach($comment->childs as $child)
                                                        <?php $cuserMeta = arrayToList($child->user->usermetas, 'option', 'value'); ?>
                                                        <li>
                                                            <img src="{{ !empty($cuserMeta['avatar']) ? $cuserMeta['avatar'] : '/assets/default/images/user.png' }}" alt=""/>
                                                            <a href="/profile/{{ $child->user_id }}">{{ $child->name }} @if($child->user->buys_count > 0) <b class="green-s">({{ trans('main.customer') }})</b> @elseif($child->user->contents_count > 0) <b class="blue-s">({{ trans('main.vendor') }})</b> @else <b class="gray-s">({{ trans('main.user') }})</b> @endif</a>
                                                            <label class="pull-left">{{ date('d F Y | H:i',$child->created_at) }}</label>
                                                            <span>{!! $child->comment !!}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(count($product->comments) > 4)
                                        <span class="btn btn-custom pull-left" id="loadMore1">{{ trans('main.load_more') }}</span>
                                    @endif
                                </ul>
                            </div>
                            @if($product->support == 1)
                                <div class="tab-pane fade blog-comment-section body-target-s" id="ctab2">
                                    @if($buy || $product->price == 0)
                                        <form method="post" action="/product/support/store">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="content_id" value="{{ $product->id }}"/>
                                            <div class="form-group">
                                                <label>{{ trans('main.private_conversation') }}</label>
                                                <textarea class="form-control" name="comment" rows="4" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-custom" value="Send">
                                            </div>
                                        </form>
                                    @elseif(isset($user) && $product->user_id == $user['id'])
                                        <div class="col-xs-12 text-center support-lock">
                                            <span>{{ trans('main.support_address') }}</span>
                                            <a href="/user/ticket/support?openid={{ $product->id }}">{{ trans('main.panel_support') }}</a>
                                            <span>{{ trans('main.support_students') }}</span>
                                            <br>
                                            <span class="mdi mdi-lock"></span>
                                        </div>
                                    @else
                                        <div class="col-xs-12 text-center support-lock">
                                            <span>{{ trans('main.support_only_students') }}</span>
                                            <br>
                                            <span class="mdi mdi-lock"></span>
                                        </div>
                                    @endif
                                    <ul class="comment-box support-list">
                                        @foreach($product->supports as $support)
                                            @if(isset($user) and $support->sender_id == $user['id'])
                                                @if($support->supporter_id != $support->user_id)
                                                    <?php $senderMeta = arrayToList($support->sender->usermetas, 'option', 'value'); ?>
                                                    <li class="user-metas">
                                                        <img src="{{ !empty($senderMeta['avatar']) ? $senderMeta['avatar'] : '/assets/default/images/user.png' }}" alt=""/>
                                                        <a href="/profile/{{ $support->user_id }}">{{ $support->name }}</a>
                                                        <label class="pull-left">
                                                            {{ date('d F Y | H:i',$support->created_at) }}
                                                        </label>
                                                        <span>{!! $support->comment !!}</span>
                                                    </li>
                                                @else
                                                    <?php $senderMeta = arrayToList($support->supporter->usermetas, 'option', 'value'); ?>
                                                    <li class="user-metas">
                                                        <img src="{{ !empty($senderMeta['avatar']) ? $senderMeta['avatar'] : '/assets/default/images/user.png' }}" alt=""/>
                                                        <a href="/profile/{{ $support->user_id }}">{{ $support->name }}</a>
                                                        <label class="pull-left text-center">
                                                            {{ date('d F Y | H:i',$support->created_at) }}
                                                            <br>
                                                            <div class="userraty urating" data-score="{{ $support->rate }}" data-id="{{ $support->id }}"></div>
                                                        </label>
                                                        <span>{!! $support->comment !!}</span>
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                        @if($buy && count($product->supports)>4)
                                            <span class="btn btn-custom pull-left" id="loadMore">{{ trans('main.load_more') }}</span>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
    <script>
        $('.raty').raty({
            starType: 'i', score: {{ ($product->rates->avg('rate')) ? $product->rates->avg('rate') : 0  }}, click: function (rate) {
                window.location = window.location.href + '/rate/' + rate;
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.answer-btn').click(function () {
                var parent = $(this).attr('answer-id');
                var title = $(this).attr('answer-title');
                $('input[name="parent"]').val(parent);
                scrollToAnchor('.back-green');
                $('textarea').attr('placeholder', ' Replied to ' + title);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.download-part').click(function (e) {
                e.preventDefault();
                window.location = $(this).attr('data-href');
            })
        });
    </script>
    <script>
        $(document).ready(function () {
            $('input[type=radio][name=buy_mode]').change(function () {
                $('#buy-price').html($(this).val() + ' {!! currencySign() !!} ');
                $('#buy_method').val($(this).attr('data-mode'));
                $('input[type=radio][name=buyMode]').removeAttr('selected');
                $('#buyBtn').attr('href', '#');
                if ($(this).attr('data-mode') == 'post') {
                    $('.table-base-price').hide();
                    $('.table-post-price').show();
                } else {
                    $('.table-base-price').show();
                    $('.table-post-price').hide();
                }
            })
        });
    </script>
    <script>
        $(document).ready(function () {
            $('input[type=radio][name=buyMode]').change(function () {
                var buyLink = '/bank/' + $(this).val() + '/pay/{{ $product->id }}/' + $('#buy_method').val();
                $('#buyBtn').attr('href', buyLink);
            })
        });
    </script>
    <script>
        $('.userraty').raty({
            starType: 'i',
            score: function () {
                return $(this).attr('data-score');
            },
            click: function (rate) {
                var id = $(this).attr('data-id');
                $.get('/product/support/rate/' + id + '/' + rate, function (data) {
                    if (data == 0) {
                        $.notify({
                            message: 'Sorry rating not submited.'
                        }, {
                            type: 'danger',
                            allow_dismiss: false,
                            z_index: '99999999',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            position: 'fixed'
                        });
                    }
                    if (data == 1) {
                        $.notify({
                            message: 'Rating Submited.'
                        }, {
                            type: 'danger',
                            allow_dismiss: false,
                            z_index: '99999999',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            position: 'fixed'
                        });
                    }
                })
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            var size_li = $(".support-list li").size();
            var x = 5;
            $('.support-list li:lt(' + x + ')').show();
            $('#loadMore').click(function () {
                x = (x + 5 <= size_li) ? x + 5 : size_li;
                $('.support-list li:lt(' + x + ')').show();
                $('#showLess').show();
                if (x == size_li) {
                    $('#loadMore').hide();
                }
            });
            $('#showLess').click(function () {
                x = (x - 5 < 0) ? 3 : x - 5;
                $('.support-list li').not(':lt(' + x + ')').hide();
                $('#loadMore').show();
                $('#showLess').show();
                if (x == 3) {
                    $('#showLess').hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var size_li = $(".support-list1 li").size();
            var x = 5;
            $('.support-list1 li:lt(' + x + ')').show();
            $('#loadMore1').click(function () {
                x = (x + 5 <= size_li) ? x + 5 : size_li;
                $('.support-list1 li:lt(' + x + ')').show();
                $('#showLess1').show();
                if (x == size_li) {
                    $('#loadMore1').hide();
                }
            });
            $('#showLess1').click(function () {
                x = (x - 5 < 0) ? 3 : x - 5;
                $('.support-list1 li').not(':lt(' + x + ')').hide();
                $('#loadMore1').show();
                $('#showLess1').show();
                if (x == 3) {
                    $('#showLess1').hide();
                }
            });
        });
    </script>
    <script>
        $('#buy-btn').click(function () {
            if ($('input[name="buy_mode"]:checked').attr('data-mode') == 'download') {
                $('#btn-address').hide();
                $('#postAddress').slideUp();
                $('#postAddressText').slideUp();

            } else {
                $('#btn-address').show();
                $('#postAddressText').show();
            }
        })
    </script>
    <script>
        $('#gift-card-check').click(function () {
            var code = $('#gift-card').val();
            if (code == '') {
                $.notify({
                    message: 'Please fillout all inputs.'
                }, {
                    type: 'danger',
                    allow_dismiss: false,
                    z_index: '99999999',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    position: 'fixed'
                });
            } else {
                $('#gift-card-result').html('<div class="loader"></div> Please wait...');
                $.get('/gift/' + code, function (data) {
                    if (data == 0) {
                        $('#gift-card-result').html('<b class="red-r">Sorry code is invalid.</b>');
                    } else {
                        if (data.type == 'gift')
                            $('#gift-card-result').html('<b class="green-s"> Congratulations! {!! currencySign() !!}' + data.off + ' Discount applied successfully!</b>');
                        if (data.type == 'off')
                            $('#gift-card-result').html('<b class="green-s"> Congratulations! %' + data.off + ' Discount applied successfully!</b>');
                    }
                })
            }
        });
    </script>
    <script>
        $('.buy-mode').on('change', function () {
            if ($(this).is(':checked')) {
                let buyMode = $(this).val();
                $('.btn-subscribe').each(function () {
                    let url = '/product/subscribe/' + $(this).attr('p-id') + '/' + $(this).attr('s-type') + '/' + buyMode;
                    $(this).attr('href', url);
                });
            }
        });
    </script>
    @if($buy and isset($user))
        <script>
            usage({!! $product->id !!},{!! $user['id'] !!});
        </script>
    @endif
    @if($product->discount != null || $product->category->discount != null)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
        <script>
            var Countdown = {
                $el: $('.countdown'),
                countdown_interval: null,
                total_seconds: 18000,
                init: function () {
                    this.$ = {
                        hours: this.$el.find('.bloc-time.hours .figure'),
                        minutes: this.$el.find('.bloc-time.min .figure'),
                        seconds: this.$el.find('.bloc-time.sec .figure')
                    };
                    this.values = {
                        hours: this.$.hours.parent().attr('data-init-value'),
                        minutes: this.$.minutes.parent().attr('data-init-value'),
                        seconds: this.$.seconds.parent().attr('data-init-value'),
                    };
                    this.total_seconds = this.values.hours * 60 * 60 + (this.values.minutes * 60) + this.values.seconds;
                    this.count();
                },
                count: function () {
                    var that = this,
                        $hour_1 = this.$.hours.eq(0),
                        $hour_2 = this.$.hours.eq(1),
                        $min_1 = this.$.minutes.eq(0),
                        $min_2 = this.$.minutes.eq(1),
                        $sec_1 = this.$.seconds.eq(0),
                        $sec_2 = this.$.seconds.eq(1);
                    this.countdown_interval = setInterval(function () {
                        if (that.total_seconds > 0) {
                            --that.values.seconds;
                            if (that.values.minutes >= 0 && that.values.seconds < 0) {
                                that.values.seconds = 59;
                                --that.values.minutes;
                            }
                            if (that.values.hours >= 0 && that.values.minutes < 0) {
                                that.values.minutes = 59;
                                --that.values.hours;
                            }
                            that.checkHour(that.values.hours, $hour_1, $hour_2);
                            that.checkHour(that.values.minutes, $min_1, $min_2);
                            that.checkHour(that.values.seconds, $sec_1, $sec_2);
                            --that.total_seconds;
                        } else {
                            clearInterval(that.countdown_interval);
                        }
                    }, 1000);
                },
                animateFigure: function ($el, value) {
                    var that = this,
                        $top = $el.find('.top'),
                        $bottom = $el.find('.bottom'),
                        $back_top = $el.find('.top-back'),
                        $back_bottom = $el.find('.bottom-back');
                    $back_top.find('span').html(value);
                    $back_bottom.find('span').html(value);
                    TweenMax.to($top, 0.8, {
                        rotationX: '-180deg',
                        transformPerspective: 300,
                        ease: Quart.easeOut,
                        onComplete: function () {
                            $top.html(value);
                            $bottom.html(value);
                            TweenMax.set($top, {rotationX: 0});
                        }
                    });
                    TweenMax.to($back_top, 0.8, {
                        rotationX: 0,
                        transformPerspective: 300,
                        ease: Quart.easeOut,
                        clearProps: 'all'
                    });
                },
                checkHour: function (value, $el_1, $el_2) {
                    var val_1 = value.toString().charAt(0),
                        val_2 = value.toString().charAt(1),
                        fig_1_value = $el_1.find('.top').html(),
                        fig_2_value = $el_2.find('.top').html();

                    if (value >= 10) {
                        if (fig_1_value !== val_1) this.animateFigure($el_1, val_1);
                        if (fig_2_value !== val_2) this.animateFigure($el_2, val_2);
                    } else {
                        if (fig_1_value !== '0') this.animateFigure($el_1, 0);
                        if (fig_2_value !== val_1) this.animateFigure($el_2, val_1);
                    }
                }
            };
            Countdown.init();
        </script>
    @endif
@endsection
