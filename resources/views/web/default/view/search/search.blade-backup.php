@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ get_option('site_title','') }} Search - {{ !empty(request()->get('q')) ? request()->get('q') : '' }}
@endsection
@section('page')
<?php
$base_url=baseURL();
?>
    <div class="container-fluid">
        <div class="row cat-search-section">
            <div class="container">
                <div class="col-md-6 col-sm-6 col-xs-12 cat-icon-container">
                    <span> {{ !empty($search_title) ? $search_title : 'Search' }} "{{ !empty(request()->get('q')) ? request()->get('q') : '' }}"</span>
                </div>
                <div class="col-md-3">
                    <div class="h-10"></div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <form>
                        {{ csrf_field() }}
                        <select class="form-control font-s" name="search_type">
                            @foreach ($searchTypes as $index => $searchType)
                                <option value="content_title" @if(!empty(request()->get('type')) && request()->get('type') == $index) selected @endif>{{ $searchType }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="h-10"></div>
    <div class="h-20"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="col-md-12 col-xs-12">
                    @if(empty(request()->get('type')) or (!empty(request()->get('type')) and request()->get('type') !== 'user_name'))
                        <div class="newest-container newest-container-b">
                            <div class="row body body-target body-target-s">
                                @if($contents)
                                    @foreach($contents as $content)
                                        <div class="col-md-3 col-sm-6 col-xs-12 pagi-content tab-con">
                                            <a href="<?=$base_url?>/product/{{ $content['id'] }}" title="{{ $content['title'] ?? '' }}" class="content-box">
                                                <img src="{{ $content['metas']['thumbnail'] ?? '' }}"/>
                                                <h3>{!! truncate($content['title'],30) !!}</h3>
                                                <div class="footer">
                                                    <label class="pull-right">{!! contentDuration($content['id']) !!}</label>
                                                    <span class="boxicon mdi mdi-clock pull-right"></span>
                                                    <span class="boxicon mdi mdi-wallet pull-left"></span>
                                                    @if(isset($content['metas']['price']) and $content['metas']['price'] > 0)
                                                        <label class="pull-left">{{ currencySign() }}{{ $content['metas']['price'] }}</label>
                                                    @else
                                                        <label class="pull-left">{{ trans('main.free_item') }}</label>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <h3>{{ trans('main.no_search_result') }}</h3>
                                @endif
                            </div>
                            <div class="h-10"></div>
                            @if($contents)
                                <div class="pagi text-center center-block col-xs-12"></div>
                            @endif
                        </div>
                    @else
                        <div class="newest-container newest-container-b">
                            <div class="row body body-target body-target-s">
                                @if($contents)
                                    @foreach($contents as $content)
                                        <div class="col-md-2 col-sm-3 col-xs-6 pagi-content">
                                            <a href="<?=$base_url?>/prfile/{{ $content['id'] }}" title="{{ $content['name'] }}" class="user-box pagi-content-box">
                                                <img src="{{ $content['metas']['avatar'] }}"/>
                                                <h3>{!! $content['name'] !!}</h3>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <h3>{{ trans('main.no_search_result') }}</h3>
                                @endif
                            </div>
                            <div class="h-10"></div>
                            @if($contents)
                                <div class="pagi text-center center-block col-xs-12"></div>
                            @endif
                        </div>
                    @endif
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
                items: {!! count($contents) !!},
                itemsOnPage: category_content_count,
                cssStyle: 'light-theme',
                prevText: '<i class="fa fa-angle-left"></i>',
                nextText: '<i class="fa fa-angle-right"></i>',
                onPageClick: function (pageNumber, event) {
                    pagination('.body-target', category_content_count, pageNumber - 1);
                }
            });
        });
    </script>
    <script type="application/javascript" src="<?=$base_url?>/assets/default/javascripts/category-page-custom.js"></script>
@endsection
