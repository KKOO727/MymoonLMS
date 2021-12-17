@extends('admin.newlayout.layout',['breadcom'=>['Settings','Pages']])
@section('title')
    {{ trans('admin.custom_pages') }}
@endsection
@section('page')

    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#terms" data-toggle="tab">{{ trans('admin.rules') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact" data-toggle="tab">{{ trans('admin.contact') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about" data-toggle="tab">{{ trans('admin.about') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#help" data-toggle="tab">{{ trans('admin.help') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#update" data-toggle="tab">{{ trans('admin.extra1') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#delete" data-toggle="tab">{{ trans('admin.extra2') }}</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="terms" class="tab-pane active">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_terms">{!! $_setting['pages_terms'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_terms" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="contact" class="tab-pane fade">
                    <form method="post" action="/admin/setting/store/contact" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_contact">{!! $_setting['pages_contact'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_contact" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="about" class="tab-pane fade">
                    <form method="post" action="/admin/setting/store/about" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_about">{!! $_setting['pages_about'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_about" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="help" class="tab-pane fade">
                    <form method="post" action="/admin/setting/store/help" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_help">{!! $_setting['pages_help'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_help" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="update" class="tab-pane fade">
                    <form method="post" action="/admin/setting/store/update" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_content_update">{!! $_setting['pages_content_update'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_content_update" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="delete" class="tab-pane fade">
                    <form method="post" action="/admin/setting/store/delete" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="pages_content_delete">{!! $_setting['pages_content_delete'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                <a href="/page/pages_content_delete" target="_blank" class="btn btn-danger" type="submit">{{ trans('admin.preview') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
