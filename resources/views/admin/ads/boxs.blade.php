@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Banners']])
@section('title')
    {{ trans('admin.banners_list') }}
@endsection
@section('page')

    <div class="card card-primary">
        <div class="card-body">
            <form method="post" action="/admin/setting/store">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('admin.top_left') }}</label>
                            <div class="input-group" style="display: flex">
                                <button type="button" data-input="triangle-banner-top-image" data-preview="holder" class="lfm_image btn btn-primary">
                                    Choose
                                </button>
                                <input id="triangle-banner-top-image" class="form-control" type="text" name="triangle-banner-top-image" dir="ltr" value="{{ get_option('triangle-banner-top-image') }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="triangle-banner-top-image">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('admin.top_left_link') }}</label>
                            <input type="text" class="form-control" name="triangle-banner-top-url" value="{{ get_option('triangle-banner-top-url') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('admin.bottom_left') }}</label>

                            <div class="input-group" style="display: flex">
                                <button type="button" data-input="triangle-banner-bottom-image" data-preview="holder" class="lfm_image btn btn-primary">
                                    Choose
                                </button>
                                <input id="triangle-banner-bottom-image" class="form-control" type="text" name="triangle-banner-bottom-image" dir="ltr" value="{{ get_option('triangle-banner-bottom-image') }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="triangle-banner-bottom-image">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('admin.bottom_left_link') }}</label>
                            <input type="text" class="form-control" name="triangle-banner-bottom-url" value="{{ get_option('triangle-banner-bottom-url') }}">
                        </div>
                    </div>
                </div>
                <div class="h-15"></div>
                <div class="form-group">
                    <label>{{ trans('admin.bottom_sticky') }}</label>
                    <textarea class="form-control text-left" dir="ltr" rows="10" name="banner-html-box">{!! get_option('banner-html-box','') !!}</textarea>
                </div>
                <div class="form-group">
                    <div class="h-15"></div>
                    <input type="submit" value="Save Changes" class="btn btn-primary pull-left">
                </div>
            </form>
        </div>
    </div>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.th_title') }}</th>
                    <th class="text-center">{{ trans('admin.position') }}</th>
                    <th class="text-center">{{ trans('admin.banner_size') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $list)
                    <tr>
                        <th>{{ $list->title }}</th>
                        <th class="text-center">
                            @if(!empty($list->position) and in_array($list->position, array_flip(\App\Models\AdsBox::$positions)))
                                {{ trans('admin.'.\App\Models\AdsBox::$positions[$list->position]) }}
                            @endif
                        </th>
                        <th class="text-center" width="200">
                            @if (!empty($list->size) and in_array($list->size,\App\Models\AdsBox::$sizes))
                                {{ array_search($list->size,\App\Models\AdsBox::$sizes) }}
                            @endif
                        </th>
                        <th class="text-center">
                            @if($list->mode == 'publish')
                                <span class="color-green">{{ trans('admin.active') }}</span>
                            @elseif($list->mode == 'draft')
                                <span class="color-orange">{{ trans('admin.disabled') }}</span>
                            @endif
                        </th>
                        <th class="text-center">
                            <a href="/admin/ads/box/edit/{{ $list->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/ads/box/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection


