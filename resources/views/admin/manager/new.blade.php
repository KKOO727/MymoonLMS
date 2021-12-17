@extends('admin.newlayout.layout',['breadcom'=>['Employees','New Employee']])
@section('title')
    {{ trans('admin.new_employee') }}
@endsection
@section('page')

    @if( ! empty(session('ErrorEmail')))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ trans('admin.email_exists') }}</strong>
        </div>
    @endif

    @if( ! empty(session('ErrorUsername')))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ trans('admin.username') }}</strong>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div id="main" class="tab-pane active">
                <form method="post" action="/admin/manager/new/store" class="form-horizontal form-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.real_name') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="inputDefault">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.username') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="username" class="form-control text-left" dir="ltr" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.password') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="password" class="form-control text-left" dir="ltr" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.email') }}</label>
                        <div class="col-md-6">
                            <input type="text" name="email" class="form-control text-left" dir="ltr" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('admin.th_status') }}</label>
                        <div class="col-md-6">
                            <select name="mode" class="form-control populate">
                                <option value="active">{{ trans('admin.active') }}</option>
                                <option value="deactive">{{ trans('admin.banned') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


