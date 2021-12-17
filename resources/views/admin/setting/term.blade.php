@extends('admin.newlayout.layout',['breadcom'=>['Settings','Rule & Terms']])
@section('title')
    {{ trans('admin.rules') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#request" data-toggle="tab"> {{ trans('admin.request_rules') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#term" data-toggle="tab">{{ trans('admin.publish_course') }}</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="request" class="tab-pane active">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="request_term">{!! $_setting['request_term'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="term" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="content_terms" required>{{ $_setting['content_terms'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

