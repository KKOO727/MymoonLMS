
<div class="container-fluid">
    <div class="container news-container">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-6 news-section">
                <div class="row contents_box">
                    <div class="header">
						<i class="secicon mdi mdi-script-text"></i>
                        <span>{{ trans('main.latest_articles') }} </span>
                    </div>
                    <div class="body">
                        <ul>
                            @foreach($article_post as $article)
                                <li>
                                    <a href="{!! baseURL() !!}/article/item/{{ $article->id }}">
                                        <img src="{!! baseURL() !!}{{ $article->image }}" alt=""><span>{{ $article->title }}</span><label for="">{{ date('l d F Y',$article->created_at) }}</label>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="more-link">
						<i class="secicon mdi mdi-dots-horizontal"></i>
                        <a href="{!! baseURL() !!}/article/list">{{ trans('main.more') }}</a>
                    </div>
                </div>
                <div class="h-10"></div>
                <div class="row contents_box">
                    <div class="header header-news">
						<i class="secicon mdi mdi-clipboard-text"></i>
                        <span>{{ trans('main.latest_news') }}</span>
                    </div>
                    <div class="body">
                        <ul>
                            @foreach($blog_post as $post)
                                <li><a href="{!! baseURL() !!}/blog/post/{{ $post->id }}"><img src="{!! baseURL() !!}{{ $post->image }}" alt=""><span>{{ $post->title }}</span><label for="">{{ date('l d F Y',$post->created_at) }}</label></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="more-link">
						<i class="secicon mdi mdi-dots-horizontal"></i>
                        <a href="{!! baseURL() !!}/blog">{{ trans('main.more') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xs-12 col-sm-6">
                <div class="row-xs">
                    <div class="two-ads-container">
                        <div class="h-10 visible-xs"></div>
                        @if(isset($ads))
                            <div class="row">
                                @foreach($ads as $ad)
                                    @if($ad->position == 'main-article-side')
                                        <a href="{!! baseURL() !!}{{ $ad->url }}"><img src="{!! baseURL() !!}{{ $ad->image }}" class="{{ $ad->size }}"></a>
                                    @endif
                                @endforeach
                                <div class="h-15"></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row-xs contents_box">
                    <div class="top-user-container">
                        <div class="header">
							<i class="secicon mdi mdi-teach"></i>
                            <span class="best-users">{{ trans('main.top_vendors') }}</span>
                        </div>
                        <div class="user-tabs">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="active"><a href="#tab1" role="tab" data-toggle="tab">{{ trans('main.courses_feedback') }}</a></li>
                                <li><a href="#tab2" role="tab" data-toggle="tab">{{ trans('main.courses_count') }}</a></li>
                                <li><a href="#tab3" role="tab" data-toggle="tab">{{ trans('main.sales') }}</a></li>
                            </ul>
                            <!-- TAB CONTENT -->
                            <div class="tab-content">
                                <div class="active tab-pane fade in" id="tab1">
                                    @if(isset($user_rate))
                                        @foreach($user_rate as $ur)
                                            <?php $meta = arrayToList($ur->usermetas,'option','value'); ?>
                                            <div class="col-md-3 tab-con">
                                        <a href="{!! baseURL() !!}/profile/{{ $ur->id }}">
                                            <img src="{!! baseURL() !!}{{ !empty($meta['avatar']) ? $meta['avatar'] : '/assets/default/images/user.png' }}">
                                            <span>{{ !empty($ur->name) ? $ur->name : '' }}</span>
                                        </a>
                                    </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="tab2">
                                    @if(isset($user_content))
                                        @foreach($user_content as $uc)
                                            <?php $meta = arrayToList($uc->usermetas,'option','value'); ?>
                                            <div class="col-md-3 tab-con">
                                                <a href="{!! baseURL() !!}/profile/{{ $uc->id }}">
                                                    <img src="{!! baseURL() !!}{{ !empty($meta['avatar']) ? $meta['avatar'] : '/assets/default/images/user.png' }}">
                                                    <span>{{ !empty($ur->name) ? $ur->name : '' }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="tab3">
                                    @if(isset($user_popular))
                                        @foreach($user_popular as $up)
                                            <?php $meta = arrayToList($up->usermetas,'option','value'); ?>
                                            <div class="col-md-3 tab-con">
                                                <a href="{!! baseURL() !!}/profile/{{ $up->id }}">
                                                    <img src="{!! baseURL() !!}{{ !empty($meta['avatar']) ? $meta['avatar'] : '/assets/default/images/user.png' }}">
                                                    <span>{{ !empty($ur->name) ? $ur->name : '' }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-10"></div>
                <div class="row-xs contents_box">
                    <div class="top-user-container">
                        <div class="header">
							<i class="secicon mdi mdi-bullhorn"></i>
                            <span class="best-chanels">{{ trans('main.top_channels') }}</span>
                        </div>
                        <div class="user-tabs">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="active"><a href="#tab4" role="tab" data-toggle="tab">{{ trans('main.newest') }}</a></li>
                                <li><a href="#tab5" role="tab" data-toggle="tab">{{ trans('main.most_viewed') }}</a></li>
                                <li><a href="#tab6" role="tab" data-toggle="tab">{{ trans('main.best_rated') }}</a></li>
                            </ul>
                            <!-- TAB CONTENT -->
                            <div class="tab-content">
                                <div class="active tab-pane fade in" id="tab4">
                                    @if(isset($channels))
                                        @foreach($channels['new'] as $ur)
                                            <div class="col-md-3 tab-con">
                                                <a href="{!! baseURL() !!}/chanel/{{ $ur->username }}">
                                                    <img src="{!! baseURL() !!}{{ !empty($ur->avatar) ? $ur->avatar : '/assets/default/images/user.png' }}">
                                                    <span>{{ !empty($ur->title) ? $ur->title : '' }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="tab5">
                                    @if(isset($channels))
                                        @foreach($channels['view'] as $ur)
                                            <div class="col-md-3 tab-con">
                                                <a href="{!! baseURL() !!}/chanel/{{ $ur->username }}">
                                                    <img src="{!! baseURL() !!}{{ !empty($ur->avatar) ? $ur->avatar : '/assets/default/images/user.png' }}">
                                                    <span>{{ !empty($ur->title) ? $ur->title : '' }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="tab6">
                                    @if(isset($channels))
                                        @foreach($channels['popular'] as $ur)
                                            <div class="col-md-3 tab-con">
                                                <a href="{!! baseURL() !!}/chanel/{{ $ur->username }}">
                                                    <img src="{!! baseURL() !!}{{ !empty($ur->avatar) ? $ur->avatar : '/assets/default/images/user.png' }}">
                                                    <span>{{ !empty($ur->title) ? $ur->title : '' }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
