@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ get_option('site_title','') }} - {{ !empty($category->title) ? $category->title : 'Categories' }}
@endsection
@section('page')
    <?php $base_url = baseURL(); ?>



    <div class="container">
        <div class="row" style="padding-top:30px;padding-bottom:15px">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="box box-s">
                    <div class="container-2">
                        <form>
                            {{ csrf_field() }}
                            <input type="search" id="search" name="q"
                                   value="{{ !empty(request()->get('q')) ? request()->get('q') : ''  }}"
                                   placeholder=" {{ !empty($category->title) ? $category->title : 'Search in all categories' }}"/>
                            <span class="icon"><i class="homeicon mdi mdi-magnify"></i></span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <span style="font-size:24px;line-height:46px;">Online Arabic tutors & teachers </span> <span
                    class="btn btn-gray"
                    style="border-radius:20px;border:1px solid #e1e1e1;background:#e1e1e1;margin-top:-10px"> {{$teachers->count()}} tutors available</span><br>
                <span style="font-size:15px">Choose one of our experienced English teachers and receive the best learning experience online!</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row cat-tag-section">

            <div class="col-md-3 col-xs-12 tab-con">
                <select class="form-control select2" placeholder="I want to learn" name="category" id="category" multiple>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{ trans($category->title) }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-2 col-xs-12 tab-con">

            </div>
            <div class="col-md-5 col-xs-12 text-left tab-con">

            </div>
            <div class="col-md-2 col-xs-12 text-left tab-con">
                <div class="form-group pull-left">
                    <select class="form-control" id="order">
                        <option value="new" @if($order == 'new') selected @endif>{{ trans('main.newest') }}</option>
                        <option value="old" @if($order == 'old') selected @endif>{{ trans('main.oldest') }}</option>
                        <option value="price"
                                @if($order == 'price') selected @endif>{{ trans('main.price_ascending') }}</option>
                        <option value="cheap"
                                @if($order == 'cheap') selected @endif>{{ trans('main.price_descending') }}</option>
                        <option value="sell"
                                @if($order == 'sell') selected @endif>{{ trans('main.most_sold') }}</option>
                        <option value="popular"
                                @if($order == 'popular') selected @endif>{{ trans('main.most_popular') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="h-20"></div>
    <div class="container">

        <?php $vipIds = array();?>
        @foreach($teachers as $teacher)

            <div class="">
                <div class="row teacher-row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            $userMetas = get_all_user_meta($teacher->id);
                            ?>
                            <div class="col-md-4">
                                <img src="{!! baseURL() !!}{{ !empty($userMetas['profile_image']) ? $userMetas['profile_image'] : '/assets/admin/img/default-video-cover.jpg' }}" style="width:100%;border-radius:8px;">

                            </div>
                            <div class="col-md-6" style="border-right:1px solid #e1e1e1">
                                <h3>{{ !empty($teacher->name) ? $teacher->name : '' }}</h3>
                                <span style="color:#cccccc">{{ substr(@$userMetas['short_biography'], 0,100) }}</span>
                                <br><br>
                                <ul>
                                    <li><span class="proicon mdi mdi-apps"></span> <strong>Type</strong> 1 to 1
                                        Class | Group Course | Video Courses
                                    </li>
                                    <li><span class="proicon mdi mdi-message"></span> <strong>Speaks</strong>
                                        English <span class="">Native</span> | Arabic <span
                                            class="">Intermediate</span>
                                        | Spanish <span class="">Beginner</span></li>
                                    <li><span class="proicon mdi mdi-buffer"></span>
                                        <strong>{{$teacher->contents->count()}} Courses</strong></li>

                                </ul>
                            </div>
                            <div class="col-md-2">
                                <div class="image-box text-center">

                                    <img
                                        src="{!! baseURL() !!}{{ !empty($userMetas['avatar']) ? $userMetas['avatar'] : '/assets/admin/img/avatar/avatar-1.png' }}">
                                    <br><span style="color:#cccccc">{{$teacher->contents->count()}} Courses</span>

                                        <div class="rate-section raty" style="cursor: pointer;"><i data-alt="1" class="star-on-png" title="bad"></i>&nbsp;<i data-alt="2" class="star-on-png" title="poor"></i>&nbsp;<i data-alt="3" class="star-on-png" title="regular"></i>&nbsp;<i data-alt="4" class="star-on-png" title="good"></i>&nbsp;<i data-alt="5" class="star-off-png" title="gorgeous"></i><input name="score" type="hidden"></div>

                                        <a href="/profile/{{$teacher->id}}" class="btn btn-primary">Book</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="h-10"></div>
    <div class="pagi text-center center-block col-xs-12"></div>
    <div class="row pagi-s">
        @if(isset($ads))
            @foreach($ads as $ad)
                @if($ad->position == 'category-pagination-bottom')
                    <a href="<?=$base_url?>{{ $ad->url }}"><img src="{{ $ad->image }}"
                                                                class="{{ $ad->size }}"
                                                                id="cat-side"></a>
                @endif
            @endforeach
        @endif
    </div>
    </div>

@endsection
@section('script')
    <style>
        .star-on-png,.star-off-png{
            color:#ffa60f;font-size:16px;
        }

    </style>
    <script>
        $(function () {
            pagination('.body-target', {{ !empty($setting['site']['category_content_count']) ? $setting['site']['category_content_count'] : 6 }}, 0);
            $('.pagi').pagination({
                items: {!! count($teachers) !!},
                itemsOnPage: {{ !empty($setting['site']['category_content_count']) ? $setting['site']['category_content_count'] : 6 }},
                cssStyle: 'light-theme',
                prevText: 'Pre.',
                nextText: 'Next',
                onPageClick: function (pageNumber, event) {
                    pagination('.body-target', {{ !empty($setting['site']['category_content_count']) ? $setting['site']['category_content_count'] : 6 }}, pageNumber - 1);
                }
            });
        });
    </script>
    <script type="application/javascript" src="/assets/default/javascripts/category-page-custom.js"></script>
@endsection
