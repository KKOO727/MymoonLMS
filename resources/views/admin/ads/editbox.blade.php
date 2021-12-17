@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Edit Banner']])
@section('title')
    {{ trans('admin.edit_banner') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            <form action="/admin/ads/box/edit/store/{{ $item->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-5">
                        <input type="text" name="title" value="{{ $item->title }}" class="form-control" required>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.position') }}</label>
                    <div class="col-md-5">
                        <select name="position" class="form-control">
                            @foreach (\App\Models\AdsBox::$positions as $index => $position)
                                <option value="{{ $index }}" @if(isset($item->position) and $item->position == $index) selected @endif>{{ trans('admin.'.$position) }}</option>
                            @endforeach
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
                            <input id="image" class="form-control" type="text" name="image" value="{{ $item->image }}" dir="ltr" required>
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
                                <option value="{{ $size }}" @if(isset($item->size) && $item->size == $size) selected @endif>{{ $index }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.link_address') }}</label>
                    <div class="col-md-5">
                        <input type="text" value="{{ $item->url }}" name="url" dir="ltr" class="form-control text-left">
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.sort') }}</label>
                    <div class="col-md-5">
                        <input type="number" min="0" max="99" value="{{ $item->sort }}" name="sort" dir="ltr" class="form-control text-center">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="custom-switches-stacked">
                            <label class="custom-switch">
                                <input type="hidden" name="mode" value="draft">
                                <input type="checkbox" name="mode" value="publish" class="custom-switch-input" @if($item->mode=='publish') checked @endif />
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


