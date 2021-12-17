@extends('admin.newlayout.layout',['breadcom'=>['Advertising','New Banner']])
@section('title')
    {{ trans('admin.new_banner') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">

            <form action="/admin/ads/box/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-5">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.position') }}</label>
                    <div class="col-md-5">
                        <select name="position" class="form-control">
                            <option value="main-slider-side">{{ trans('admin.homepage_slider') }}</option>
                            <option value="main-article-side">{{ trans('admin.homepage_articles') }}</option>
                            <option value="category-side">{{ trans('admin.cat_page_sidebar') }}</option>
                            <option value="category-pagination-bottom">{{ trans('admin.cat_page_bottom') }}</option>
                            <option value="product-page">{{ trans('admin.product_page') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.image') }}</label>
                    <div class="col-md-5">
                        <div class="input-group" style="display: flex">
                            <button type="button" data-input="image" data-preview="holder" class="lfm_image btn btn-primary">
                                Choose
                            </button>
                            <input id="image" class="form-control" type="text" name="image" dir="ltr" required>
                            <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.banner_size') }}</label>
                    <div class="col-md-5">
                        <select name="size" class="form-control">
                            @foreach (\App\Models\AdsBox::$sizes as $index => $size)
                                <option value="{{ $size }}">{{ $index }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.link_address') }}</label>
                    <div class="col-md-5">
                        <input type="text" name="url" dir="ltr" class="form-control text-left" required>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.sort') }}</label>
                    <div class="col-md-5">
                        <input type="number" min="0" max="99" name="sort" dir="ltr" class="form-control text-center">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="custom-switches-stacked">
                            <label class="custom-switch">
                                <input type="hidden" name="mode" value="draft">
                                <input type="checkbox" name="mode" value="publish" class="custom-switch-input"/>
                                <span class="custom-switch-indicator"></span>
                                <label class="custom-switch-description" for="inputDefault">{{ trans('admin.publish_item') }}</label>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection

