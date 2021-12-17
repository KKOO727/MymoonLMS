@extends('admin.newlayout.layout')
@include('admin.layout.pageheader',array('breadcom'=>['Blog','Comments','Reply']));
@section('title')
    {{ trans('admin.reply_comment') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            {!! $item->comment !!}
        </div>
    </section>
    <section class="card">
        <header class="card-header">
            <h6 class="card-title">{{ trans('admin.reply_comment') }}</h6>
        </header>
        <div class="card-body">

            <form method="post" action="/admin/blog/comment/reply/store" class="form-horizontal form-bordered">
                {{ csrf_field() }}
                <input type="hidden" name="parent" value="{{ $item->id }}">
                <input type="hidden" name="post_id" value="{{ $item->post_id }}">

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="comment" required></textarea>
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


