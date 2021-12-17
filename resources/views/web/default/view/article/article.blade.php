@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    - {{ !empty($post->title) ? $post->title : '' }}
@endsection

@section('page')

    <div class="container-fluid">
        <div class="container">
            <div class="blog-section">
                <div class="col-xs-12 row blog-post-box blog-post-box-s">
                    <div class="col-md-4 col-xs-12">
                        <img src="{{ $post->image }}" class="img-responsive">
                        <span class="date-section">{{ date('d F Y',$post->created_at) }}</span>
                        <span class="date-section date-section-s">
                                <img src="{{ !empty($post->category->image) ? $post->category->image : '' }}" class="img-responsive pull-left">
                                <a href="/category/{{ !empty($post->category->class) ? $post->category->class : '' }}" class="pull-left a-link-s">{{ $post->category->title ?? '' }}</a>
                            </span>
                        <div class="product-user-box">
                            <img class="img-box" src="{{ !empty($userMeta['avatar']) ? $userMeta['avatar'] : get_option('default_user_avatar','') }}" class="img-responsive"/>
                            <span>{{ $post->user->name }}</span>
                            <div class="user-description-box">
                                {{ !empty($userMeta['short_biography']) ? $userMeta['short_biography'] : '' }}
                            </div>
                            <div class="text-center">
                                @foreach($rates as $rate)
                                    <img class="img-icon img-icon-s" src="{{ !empty($rate['image']) ? $rate['image'] : '' }}" title="{{ !empty($rate['description']) ? $rate['description'] : '' }}"/>
                                @endforeach
                            </div>
                            <div class="h-10"></div>
                            <div class="product-user-box-footer">
                                <a href="/profile/{{ $post->user->id }}">{{ trans('main.user_profile') }}</a>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8 col-xs-12 text-section">
                        <h1 class="text-section-s1">{{ $post->title }}</h1>
                        <br>
                        {!!   !empty($post->pre_text) ? $post->pre_text : '' !!}
                        <hr>
                        {!!   !empty($post->text) ? $post->text : '' !!}
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="col-xs-12 col-md-12 article-tabs">
                <div class="user-tabs article-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#vtab1" role="tab" data-toggle="tab">{{ trans('main.related_courses') }}</a></li>
                        <li><a href="#vtab2" role="tab" data-toggle="tab">{{ trans('main.user_courses') }}</a></li>
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content articlec" style="height: auto;overflow: hidden;">
                        <div class="active tab-pane fade in tab-body" id="vtab1">
                            @foreach($relContent as $new)
                                <?php $meta = arrayToList($new->metas, 'option', 'value'); ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/product/{{ $new->id }}" title="{{ $new->title }}" class="content-box">
                                        <img src="{{ $meta['thumbnail'] }}"/>
                                        <h3>{!! truncate($new->title,35) !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">@if(isset($meta['duration'])){{ convertToHoursMins($meta['duration']) }}@else {{ trans('main.not_defined') }} @endif </label>
                                            <span class="boxicon mdi mdi-clock pull-right"></span>
                                            <span class="boxicon mdi mdi-wallet pull-left"></span>
                                            <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {{ currencySign() }}{{$meta['price']}} @else {{ trans('main.free') }} @endif</label>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade tab-body" id="vtab2">
                            @foreach($userContent as $new)
                                <?php $meta = arrayToList($new->metas, 'option', 'value'); ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/product/{{ $new->id }}" title="{{ $new->title }}" class="content-box">
                                        <img src="{{ $meta['thumbnail'] }}"/>
                                        <h3>{!! truncate($new->title,35) !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">@if(isset($meta['duration'])){{ convertToHoursMins($meta['duration']) }}@else {{ trans('main.not_defined') }} @endif </label>
                                            <span class="boxicon mdi mdi-clock pull-right"></span>
                                            <span class="boxicon mdi mdi-wallet pull-left"></span>
                                            <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {{ currencySign() }}{{$meta['price']}} @else {{ trans('main.free') }} @endif</label>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-30"></div>

@endsection
