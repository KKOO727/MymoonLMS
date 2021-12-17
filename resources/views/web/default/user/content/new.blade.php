@extends(getTemplate().'.user.layout.sendvideolayout')

@section('pages')
    <div class="h-30"></div>
    <div class="conteiner-fluid">
        <div class="container cont-10">
            <div class="h-30"></div>
            <div class="multi-steps">
                @include('web.default.user.content.tabs')
                <div class="col-md-9 col-xs-12 col-sm-8 tab-con left-side">
                    <div class="steps" id="step1">

                        <form method="post" action="/user/content/new/store" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-2 tab-con"
                                       for="inputDefault">{{ trans('main.course_type') }}</label>
                                <div class="col-md-10 tab-con">
                                    <select name="type" class="form-control font-s">

                                        <option value="1">One to One</option>
                                        <option value="2">Group Course</option>
                                        <option value="3">Video Course</option>

                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <div class="col-md-12 tab-con">
                                    <input type="submit" class="btn btn-custom pull-left" value="Next">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="steps dnone" id="step2">

                        <form method="post" action="/user/content/new/store" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2 control-label"
                                       for="inputDefault">{{ trans('main.category') }}</label>
                                <div class="col-md-10">
                                    <select name="category_id" id="category_id" class="form-control font-s" required>
                                        <option value="0">{{ trans('main.select_category') }}</option>
                                        @foreach($menus as $menu)
                                            @if($menu->parent_id == 0)
                                                <optgroup label="{{ $menu->title }}">
                                                    @if(count($menu->childs)>0)
                                                        @foreach($menu->childs as $sub)
                                                            <option value="{{ $sub->id }}">{{ $sub->title }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                                    @endif
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="h-15"></div>
                            @foreach($menus as $menu)
                                <div class="col-md-11 col-md-offset-1 filters" id="filter{{ $menu->id }}">
                                    @foreach($menu->filters as $filter)
                                        <div class="col-md-3 col-xs-12">
                                            <h5>{{ $filter->filter }}</h5>
                                            <hr>
                                            <div class="cat-filters-li pamaz">
                                                <ul class="submenu submenu-s">
                                                    @foreach($filter->tags as $tag)
                                                        <li class="second-input"><input type="checkbox"
                                                                                        class="filter-tags dblock"
                                                                                        id="tag{{ $tag->id }}"
                                                                                        name="tags[]"
                                                                                        value="{{ $tag->id }}"><label
                                                                for="tag{{ $tag->id }}"><span></span>{{ $tag->tag }}
                                                            </label></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </form>
                    </div>
                    <div class="steps dnone" id="step3">
                        <form method="post" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-md-offset-1 form-label-s"
                                       for="inputDefault">{{ trans('main.free_course') }}</label>
                                <div class="col-md-6">
                                    <div class="switch switch-sm switch-primary pull-left">
                                        <input type="hidden" value="1" name="price">
                                        <input type="checkbox" name="price" id="free" value="0" data-plugin-ios-switch/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5 col-md-offset-1 form-label-s"
                                       for="inputDefault">{{ trans('main.vendor_postal_sale') }}</label>
                                <div class="col-md-6">
                                    <div class="switch switch-sm switch-primary pull-left" id="post_toggle">
                                        <input type="hidden" value="0" name="post">
                                        <input type="checkbox" name="post" value="1" data-plugin-ios-switch/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5 col-md-offset-1 form-label-s"
                                       for="inputDefault">{{ trans('main.vendor_supports_item') }}</label>
                                <div class="col-md-6">
                                    <div class="switch switch-sm switch-primary pull-left">
                                        <input type="hidden" value="0" name="support">
                                        <input type="checkbox" name="support" value="1" data-plugin-ios-switch/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5 col-md-offset-1 form-label-s"
                                       for="inputDefault">{{ trans('main.documents') }}</label>
                                <div class="col-md-6">
                                    <div class="switch switch-sm switch-primary pull-left">
                                        <input type="hidden" value="0" name="document">
                                        <input type="checkbox" name="document" value="1" data-plugin-ios-switch/>
                                    </div>
                                </div>
                            </div>
                            <div class="h-10"></div>
                            <div class="form-group">
                                <label class="control-label col-md-2">{{ trans('main.price') }}</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" name="price" onkeypress="validate(event)"
                                               value="{{ !empty($meta['price']) ? $meta['price'] : 0 }}"
                                               class="form-control text-center numtostr" disabled>
                                        <span
                                            class="input-group-addon click-for-upload img-icon-s">@if(!empty($meta['price'])) {{ currencySign() }}{{ num2str($meta['price']) }} @endif</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">{{ trans('main.postal_price') }}</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" name="post_price" onkeypress="validate(event)"
                                               value="{{ !empty($meta['post_price']) ? $meta['post_price'] : ''}}"
                                               class="form-control text-center numtostr" disabled>
                                        <span
                                            class="input-group-addon click-for-upload img-icon-s">@if(!empty($meta['post_price'])) {{ currencySign() }}{{ num2str($meta['post_price']) }} @endif</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="h-30"></div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.editor-te').jqte({format: false});
    </script>
    <script>
        $('document').ready(function () {
            $('input[name="post"]').change(function () {
                if ($(this).prop('checked')) {
                    $('input[name="post_price"]').removeAttr('disabled');
                } else {
                    $('input[name="post_price"]').attr('disabled', 'disabled');
                }
            });
            $('#free').change(function () {
                if ($(this).prop('checked')) {
                    $('input[name="price"]').attr('disabled', 'disabled');
                    $('input[name="post_price"]').attr('disabled', 'disabled');
                } else {
                    $('input[name="price"]').removeAttr('disabled');
                }
            });
        })
    </script>
    <script>
        $('#category_id').change(function () {
            var id = $(this).val();
            $('.filter-tags').removeAttr('checked');
            $('.filters').not('#filter' + id).each(function () {
                $('.filters').slideUp();
            });
            $('#filter' + id).slideDown(500);
        })
    </script>
@endsection
