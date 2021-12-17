@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    - {{ $post->title ?? '' }}
@endsection

@section('page')

    <div class="container-fluid">
        <div class="container">
            <div class="blog-section">
                <div class="col-xs-12 row blog-post-box blog-post-box-s">
                    <div class="col-md-3 col-xs-12">
                        <img src="{{ $post->image }}" class="img-responsive">
                        <span class="date-section">{{ date('d F Y',$post->created_at) }}</span>
                        @if (!empty($post->category))
                            <span class="date-section">
                                <a href="/blog/category/{{ $post->category->id }}">{{ $post->category->title ?? '' }}</a>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-9 col-xs-12 text-section">
                        {!!   $post->pre_content !!}
                        <hr>
                        {!!   $post->content !!}
                        <br>
                        <span>{{ trans('main.tags') }} :</span>
                        @foreach(explode(',',$post->tags) as $tag)
                            <i class="content-tag"> <a href="/blog/tag/{{ $tag }}">{{ $tag }}</a> </i>
                        @endforeach
                        @if($setting['site']['blog_comment'] == 1 && $post->comment == 'enable')
                            <div class="blog-comment-section">
                                <h4>{{ trans('main.comments') }}</h4>
                                <hr>
                                <form method="post" action="<?=$base_url?>/blog/post/comment/store">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="post_id" value="{{ $post->id }}"/>
                                    <input type="hidden" name="parent" value="0"/>
                                    <div class="form-group">
                                        <label>{{ trans('main.your_comment') }}</label>
                                        <textarea class="form-control" name="comment" rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-custom pull-left" value="Send">
                                    </div>
                                </form>

                                <ul class="comment-box">
                                    @foreach($post->comments as $comment)
                                        <li>
                                            <a href="<?=$base_url?>/profile/{{ $comment->user_id }}">{{ $comment->name }}</a>
                                            <label>{{ date('d F Y | H:i',$comment->created_at) }}</label>
                                            <span>{!! $comment->comment !!}</span>
                                            <span><a href="javascript:void(0);" answer-id="{{ $comment->id }}" answer-title="{{ $comment->name }}" class="pull-left answer-btn">{{ trans('main.reply') }}</a> </span>
                                            @if(count($comment->childs)>0)
                                                <ul class="col-md-11 col-md-offset-1 answer-comment">
                                                    @foreach($comment->childs as $child)
                                                        <li>
                                                            <a href="<?=$base_url?>/profile/{{ $child->user_id }}">{{ $child->name }}</a>
                                                            <label>{{ date('d F Y | H:i',$child->created_at) }}</label>
                                                            <span>{!! $child->comment ?? '' !!}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.answer-btn').click(function () {
                var parent = $(this).attr('answer-id');
                var title = $(this).attr('answer-title');
                $('input[name="parent"]').val(parent);
                scrollToAnchor('.blog-comment-section');
                $('textarea').attr('placeholder', ' Replied to ' + title);
            });
        });
    </script>
    @if(!isset($user))
        <script>
            $(document).ready(function () {
                $('input[type="submit"]').click(function (e) {
                    e.preventDefault();
                    $('input[type="submit"]').val('Please login to your account to leave comment.')
                });
            });
        </script>
    @endif
@endsection
