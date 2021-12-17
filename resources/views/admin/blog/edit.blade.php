@extends('admin.newlayout.layout',['breadcom'=>['Blog','Edit Post']])
@section('title')
    {{ trans('admin.edit_post') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form action="/admin/blog/post/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ $item->title }}" name="title" class="form-control" required>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.category') }}</label>
                    <div class="col-md-10">
                        <select name="category_id" class="form-control select2">
                            @foreach($category as $cat)
                                <option value="{{ $cat->id }}" @if(!empty($item->category_id) and $item->category_id == $cat->id) selected @endif>{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.thumbnail') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" style="display: flex">
                            <button type="button" data-input="image" data-preview="holder" class="lfm_image btn btn-primary">
                                Choose
                            </button>
                            <input id="image" class="form-control" type="text" name="image" dir="ltr" required value="{{ $item->image }}">
                            <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="pre_content" required>{{ $item->pre_content }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="content" required>{{ $item->content }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.tags') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="tags" value="{{ $item->tags }}" data-role="tagsinput" data-tag-class="label label-primary" class="form-control">
                    </div>
                </div>

                <div class="custom-switches-stacked col-md-12">
                    <label class="custom-switch">
                        <input type="hidden" name="comment" value="disable">
                        <input type="checkbox" name="comment" value="enable" class="custom-switch-input" @if($item->comment == 'enable') {{ 'checked' }} @endif />
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.comments_enabled') }}</label>
                    </label>
                    <label class="custom-switch">
                        <input type="hidden" name="mode" value="draft">
                        <input type="checkbox" name="mode" value="publish" class="custom-switch-input" @if($item->mode == 'publish') {{ 'checked' }} @endif />
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.publish') }}</label>
                    </label>
                </div>

                <div class="h-10"></div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
