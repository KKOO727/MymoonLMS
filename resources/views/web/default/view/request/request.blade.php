@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ get_option('site_title','') }}  - {{ trans('main.requests') }}

@endsection
@section('page')

    <div class="container-fluid">
        <div class="row cat-tag-section">
            <div class="container">
                <div class="col-md-6 col-xs-12 tab-con">
                    <a href="/request/new" class="btn btn-custom"><span>{{ trans('main.request_course') }}</span></a>
                    <div class="btn-group btn-g-s" data-toggle="buttons">
                        <label class="btn btn-primary @if(empty(request()->get('mode')) or request()->get('mode') == 'all') active @endif">
                            <input type="radio" name="mode" value="all" @if(!empty(request()->get('mode')) and request()->get('mode') == 'all') checked @endif> {{ trans('main.all') }}
                        </label>
                        <label class="btn btn-primary @if(!empty(request()->get('mode')) and request()->get('mode') == 'publish') active @endif">
                            <input type="radio" name="mode" value="publish" @if(!empty(request()->get('mode')) and request()->get('mode') == 'publish') checked @endif>{{ trans('main.request') }}
                        </label>
                        <label class="btn btn-primary @if(!empty(request()->get('mode')) and request()->get('mode') == 'accept') active @endif">
                            <input type="radio" name="mode" value="accept" @if(!empty(request()->get('mode')) and request()->get('mode') == 'accept') checked @endif>{{ trans('main.closed_request') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 text-left tab-con pbot">

                </div>
                <div class="col-md-3 col-xs-12 text-left tab-con">
                    <div class="box marz">
                        <div class="container-2">
                            <form>
                                {{ csrf_field() }}
                                <input type="search" id="search" name="q" value="{{ !empty(request()->get('q')) ? request()->get('q') : '' }}" placeholder="Search in requests"/>
                                <span class="icon"><i class="homeicon mdi mdi-magnify"></i></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="h-20"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="col-md-3 col-xs-12 tab-con">
                    <div class="ucp-section-box sbox3">
                        <div class="header back-orange text-center"><span>{{ trans('main.category') }}</span></div>
                        <div class="body">
                            <ul id="accordion" class="cat-filters-li accordion">
                                @foreach($setting['category'] as $mainCategory)
                                    <li @foreach($mainCategory->childs as $child) @if(!empty(request()->get('cat')) and in_array($child->id,request()->get('cat'))) class="open" @endif @endforeach>
                                        @if(count($mainCategory->childs)>0)
                                            <div class="link">{{$mainCategory->title }}<i class="mdi mdi-chevron-down"></i></div>
                                            <ul class="submenu" @foreach($mainCategory->childs as $child) @if(!empty(request()->get('cat')) and in_array($child->id,request()->get('cat'))) style="display: block;" @endif @endforeach>
                                                @foreach($mainCategory->childs as $child)
                                                    <li><input name="category" type="checkbox" id="cat{{ $child->id }}" value="{{ $child->id }}" class="category-item" @if(!empty(request()->get('cat')) and in_array($child->id,request()->get('cat'))) checked @endif><label for="cat{{ $child->id }}"><span></span>{{ $child->title }}</label></li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="link">{{$mainCategory->title }}<i class="mdi mdi-chevron-down"></i></div>
                                            <ul class="submenu" @if(!empty(request()->get('cat')) and in_array($mainCategory->id,request()->get('cat'))) style="display: block" @endif>
                                                <li><input name="category" type="checkbox" id="cat{{ $mainCategory->id }}" value="{{ $mainCategory->id }}" class="category-item" @if(!empty(request()->get('cat')) and in_array($mainCategory->id,request()->get('cat'))) checked @endif><label for="cat{{ $mainCategory->id }}"><span></span>{{ $mainCategory->title }}</label></li>
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12">
                    <div class="newest-container newest-container-b">
                        <div class="row body body-target body-target-s">
                            @if(!empty($list))
                                @foreach($list as $content)
                                    <div class="col-md-4 col-sm-6 col-xs-12 pagi-content tab-con">
                                        <a href="{{ !empty($content->content_id) ? '/product/'. $content->content_id : 'javascript:void(0);' }}" title="{{ $content->title }}" class="content-box pagi-content-box">
                                            <img src="{{ $content->category->req_icon ?? '' }}"/>
                                            <h3>{!! truncate($content->title,30) !!}</h3>
                                            <div class="img-container text-center center-block">
                                            </div>
                                            <div class="footer">
                                                <span class="pull-right mod-r" data-toggle="modal" data-target="#dModal{{ $content->id }}"><b>{{ trans('main.description') }}</b></span>
                                                @if($content->mode == 'publish')
                                                    @if($content->content_id == '')
                                                        @if(count($content->fans) == 1)
                                                            <span class="boxicon mdi mdi-heart pull-left"></span>
                                                            <span class="pull-left request-unfollow-icon" title="{{ trans('main.unfollow') }}" onclick="window.location.href ='/request/unfollow/{{ $content->id }}'">{{ $content->fans_count }} {{ trans('main.followers') }}</span>
                                                        @else
                                                            <span class="boxicon mdi mdi-heart-outline pull-left"></span>
                                                            <span class="pull-left request-follow-icon" title="{{ trans('main.follow') }}" onclick="window.location.href ='/request/follow/{{ $content->id }}'">{{ $content->fans_count }} {{ trans('main.followers') }}</span>
                                                        @endif
                                                    @endif
                                                    @if($content->content_id != '')
                                                        <span class="boxicon mdi mdi-check-bold pull-left"></span>
                                                        <span class="pull-left request-accept-icon">{{ trans('main.closed_request') }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </a>
                                        <div id="dModal{{ $content->id }}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">{{ trans('main.description') }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ !empty($content->description) ? $content->description : 'No description' }}</p>
                                                    </div>
                                                    <div class="modal-body no-absolute-content">
                                                        @if($content->content_id != '')
                                                            <p>
                                                                <span>{{ trans('main.responded_request') }}</span>
                                                                <a href="/product/{{ $content->content->id }}"><b>{{ $content->content->title }}</b></a>
                                                            </p>
                                                        @else
                                                            <p><b>{{ trans('main.send_respond') }}</b></p>
                                                            <p>
                                                                <input type="text" id="input-suggestion_{{ $content->id }}" class="form-control provider-json" placeholder="Enter at least 3 characters to search">
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        @if($content->content_id == '')
                                                            <button type="button" class="btn btn-default btn-custom input-suggestion-btn" data-id="{{ $content->id }}">{{ trans('main.send') }}</button>
                                                        @endif
                                                        <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="h-10"></div>
                        <div class="pagi text-center center-block col-xs-12"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        var category_content_count = {{ !empty($setting['site']['category_content_count']) ? $setting['site']['category_content_count'] : 6 }}
        $(function () {
            pagination('.body-target', category_content_count, 0);
            $('.pagi').pagination({
                items: {!! count($list) !!},
                itemsOnPage: category_content_count,
                cssStyle: 'light-theme',
                prevText: 'Pre.',
                nextText: 'Next',
                onPageClick: function (pageNumber, event) {
                    pagination('.body-target', category_content_count, pageNumber - 1);
                }
            });
        });
    </script>
    <script type="application/javascript" src="/assets/default/javascripts/category-page-custom.js"></script>
@endsection
