@extends('admin.newlayout.layout',['breadcom'=>['Settings','Custom CSS & JS']])
@section('title')
    {{ trans('admin.custom_codes') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#css" data-toggle="tab"> {{ trans('admin.input_css') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#js" data-toggle="tab"> {{ trans('admin.input_js') }} </a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="css" class="tab-pane active">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control text-left h-400" style="height: 400px !important;" dir="ltr" name="main_css">{!! $_setting['main_css'] ?? '' !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="js" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control text-left h-400" style="height: 400px !important;" dir="ltr" name="main_js">{!! $_setting['main_js'] ?? '' !!}</textarea>
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
