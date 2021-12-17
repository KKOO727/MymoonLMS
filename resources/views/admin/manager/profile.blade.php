@extends('admin.newlayout.layout',['breadcom'=>['Employees','Profile',$user->name]])
@section('page')
    @if($errors->any())
        <div class="alert alert-primary">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ $errors->first() }}</strong>
        </div>
    @endif

    <div class="tabs">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#main" class="nav-link active" data-toggle="tab"> {{ trans('admin.general') }} </a>
            </li>
            <li class="nav-item">
                <a href="#security" class="nav-link" data-toggle="tab">{{ trans('admin.security') }}</a>
            </li>
        </ul>
        <div class="card">
            <div class="card-body">
                <div class=" tab-content">
                    <div id="main" class="tab-pane active">
                        <form action="/admin/profile/main/update" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.real_name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="inputDefault">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.email') }}</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ $user->email }}" id="inputReadOnly" class="form-control text-left" dir="ltr" disabled>
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
                    <div id="security" class="tab-pane">
                        <form action="/admin/profile/security/update" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_password') }}</label>
                                <div class="col-md-6">
                                    <input type="password" name="password" class="form-control" id="inputDefault" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_password_repeat') }}</label>
                                <div class="col-md-6">
                                    <input type="password" name="re_password" class="form-control" id="inputDefault" required>
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
        </div>
    </div>
@endsection

