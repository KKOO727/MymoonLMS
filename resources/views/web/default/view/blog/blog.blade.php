@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    {{ trans('main.blog') }} -
@endsection

@section('page')

    <div class="container-fluid">
        <div class="container">

            <div class="blog-section">
                @foreach($posts as $post)
                    <div class="row blog-post-box">
                        <div class="col-md-3 col-xs-12">
                            <img src="{!! baseURL() !!}{{ $post->image }}" class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 text-section">
                            <a href="{!! baseURL() !!}/blog/post/{{ $post->id }}"><h3>{{ $post->title }}</h3></a>
                            {!!   $post->pre_content !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="h-10"></div>
            <div class="pagi text-center center-block col-xs-12"></div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            pagination('.blog-section',{{ !empty($setting['site']['blog_post_count']) ? $setting['site']['blog_post_count'] : 4 }}, 0);
            $('.pagi').pagination({
                items: {!! count($posts) !!},
                itemsOnPage:{{ !empty($setting['site']['blog_post_count']) ? $setting['site']['blog_post_count'] : 4 }},
                cssStyle: 'light-theme',
                prevText: 'Pre.',
                nextText: 'Next',
                onPageClick: function (pageNumber, event) {
                    pagination('.blog-section',{{ !empty($setting['site']['blog_post_count']) ? $setting['site']['blog_post_count'] : 4 }}, pageNumber - 1);
                }
            });
        });
    </script>
@endsection
