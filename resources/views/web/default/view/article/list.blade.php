@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    {{ trans('main.articles') }} -
@endsection

@section('page')
    <div class="container-fluid">
        <div class="row cat-tag-section">
            <div class="container">
                <div class="col-md-2 col-xs-12 col-md-offset-10 text-left">
                    <div class="form-group pull-left">
                        <select class="form-control" id="order order-s">
                            <option value="new" @if(isset($order) && $order == 'new') selected @endif>{{ trans('main.newest') }}</option>
                            <option value="old" @if(isset($order) && $order == 'old') selected @endif>{{ trans('main.oldest') }}</option>
                            <option value="view" @if(isset($order) && $order == 'view') selected @endif>{{ trans('main.most_viewed') }}</option>
                            <option value="popular" @if(isset($order) && $order == 'popular') selected @endif>{{ trans('main.most_popular') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-12 tab-con">
                    <div class="h-20"></div>
                    <div class="ucp-section-box sbox3" id="ucp-section-article">
                        <div class="header back-orange header-new"><span>{{ trans('main.category') }}</span></div>
                        <div class="body">
                            <ul>
                                @foreach($category as $cat)
                                    <li>
                                        <input type="checkbox" name="category" id="category{{ $cat->id }}" value="{{ $cat->id }}" @if(isset($cats) && in_array($cat->id,$cats)) checked @endif/>
                                        <label for="category{{ $cat->id }}"><span></span>{{ $cat->title }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12">
                    <div class="row blog-section blog-section-s">
                        @foreach($posts as $post)
                            <div class="col-md-4 col-xs-12 article-post-count tab-con">
                                <div class="post-module">
                                    <div class="thumbnail">
                                        <div class="date">
                                            <div class="day">{{ date('d',$post->created_at) }}</div>
                                            <div class="month">{{ date('F',$post->created_at) }}</div>
                                        </div>
                                        <img src="{{ $post->image }}">
                                    </div>
                                    <div class="post-content">
                                        <h1 class="title">
                                            <a href="/article/item/{{ $post->id }}">
                                                <h3>{{ $post->title }}</h3>
                                            </a>
                                        </h1>
                                        <p class="description">
                                            {{  mb_strimwidth(strip_tags($post->pre_text),0,250,'...') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="h-10"></div>
                    <div class="pagi text-center center-block col-xs-12"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            pagination('.blog-section',{{ !empty($setting['site']['article_post_count']) ? $setting['site']['article_post_count'] : 6 }}, 0);
            $('.pagi').pagination({
                items: {!! count($posts) !!},
                itemsOnPage:{{ !empty($setting['site']['article_post_count']) ? $setting['site']['article_post_count'] : 6 }},
                cssStyle: 'light-theme',
                prevText: 'Pre.',
                nextText: 'Next',
                onPageClick: function (pageNumber, event) {
                    pagination('.blog-section',{{ !empty($setting['site']['article_post_count']) ? $setting['site']['article_post_count'] : 6 }}, pageNumber - 1);
                }
            });
        });
    </script>
    <script>
        $(function () {
            $('#order').change(function () {
                var url = window.location.href.replace(/#.*$/, "");
                if (url.indexOf('?') != -1)
                    var addon = '&order=' + $(this).val();
                else
                    var addon = '?order=' + $(this).val();
                window.location.href = url + addon;
            })
        })
    </script>
    <script>
        $(function () {
            $('input[type="checkbox"][name="category"]').change(function () {
                var url = window.location.href.replace(/#.*$/, "");
                var state = $(this).val();
                if (this.checked) {

                    if (url.indexOf('?') != -1)
                        var addon = '&cat[]=' + state;
                    else
                        var addon = '?cat[]=' + state;
                    window.location.href = url + addon;
                } else {
                    window.location.href = url.replace('cat[]=' + state, '');
                }
            })
        })
    </script>
@endsection
