@extends('admin.newlayout.layout',['breadcom'=>['Blog','Edit Article']])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.articles') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            <form action="/admin/blog/article/edit/store/{{ $item->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-10">
                        <input type="text" value="{{ $item->title }}" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.category') }}</label>
                    <div class="col-md-10">
                        <select name="cat_id" class="form-control">
                            @foreach($categories as $category)
                                <option value="{!! $category->id ?? '' !!}" @if($category->id == $item->cat_id) selected @endif >{!! $category->title ?? '' !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.thumbnail') }}</label>
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            </span>
                            <input type="text" name="image" id="image" value="{{ $item->image }}" dir="ltr" class="form-control">
                            <span id="click-for-upload" data-input="image" class="input-group-append click-for-upload cu-p">
                                <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="pre_text" required>{{ $item->pre_text }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="text" required>{{ $item->text }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">{{ trans('admin.th_status') }}</label>
                    <div class="col-md-10">
                        <select class="form-control f-s-1" name="mode">
                            <option value="publish" @if($item->mode == 'publish') selected @endif class="f-w-b c-g">{{ trans('admin.published') }}</option>
                            <option value="draft" @if($item->mode == 'draft') selected @endif>{{ trans('admin.draft') }}</option>
                            <option value="request" @if($item->mode == 'request') selected @endif>{{ trans('admin.review_request') }}</option>
                            <option value="delete" @if($item->mode == 'delete') selected @endif>{{ trans('admin.unpublish_request') }}</option>
                        </select>
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
@section('script')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#click-for-upload').filemanager('image');
</script>
@stop


