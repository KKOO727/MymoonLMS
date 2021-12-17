@extends('admin.newlayout.layout',['breadcom'=>['blog','Comments','edit']])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.comment') }}
@endsection

@section('page')
    <section class="panel">
        <div class="card-body">
            <form method="post" action="/admin/blog/comment/store" class="form-horizontal form-bordered">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="comment" required>{{ $item->comment }}</textarea>
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


