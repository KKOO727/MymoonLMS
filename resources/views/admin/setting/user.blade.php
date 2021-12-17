@extends('admin.newlayout.layout',['breadcom'=>['Settings','Users']])
@section('title')
    {{ trans('admin.user_setting') }}
@endsection
@section('page')

    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#main" data-toggle="tab"> {{ trans('admin.user_setting') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#seller" data-toggle="tab"> {{ trans('admin.vendor_settings') }} </a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="main" class="tab-pane active">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.activation_method') }}</label>
                            <div class="col-md-7">
                                <select class="form-control" id="user_register_mode" name="user_register_mode">
                                    <option value="active" @if(isset($_setting['user_register_mode']) and $_setting['user_register_mode'] == 'active') selected @endif>{{ trans('admin.quick_activation') }}</option>
                                    <option value="deactive" @if(isset($_setting['user_register_mode']) and $_setting['user_register_mode'] == 'deactive') selected @endif>{{ trans('admin.email_verification') }}</option>
                                </select>
                            </div>
                        </div>

                        @if(isset($_setting['user_register_mode']) and $_setting['user_register_mode'] == 'deactive')
                            <div class="form-group" id="user_register_active_email">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.email_verification_template') }}</label>
                                <div class="col-md-7">
                                    <select id="template" name="user_register_active_email" class="form-control">
                                        <option value=""></option>
                                        @foreach($template as $temp)
                                            <option value="{{ $temp->id }}" @if(isset($_setting['user_register_active_email']) and $temp->id == $_setting['user_register_active_email']) selected @endif>{{ $temp->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.user_welcome_email') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="user_register_wellcome_email" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['user_register_wellcome_email']) and $temp->id == $_setting['user_register_wellcome_email']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.pass_change_email_template') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="user_register_reset_email" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['user_register_reset_email']) and $temp->id == $_setting['user_register_reset_email']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_pass_email_template') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="user_register_new_password_email" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['user_register_new_password_email']) and $temp->id == $_setting['user_register_new_password_email']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.notification_email_template') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="email_notification_template" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['email_notification_template']) and $temp->id == $_setting['email_notification_template']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
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
                <div id="seller" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.not_identity_verified_alert') }}</label>
                            <div class="col-md-9">
                                <textarea class="form-control h-400" name="seller_not_apply">{{ $_setting['seller_not_apply'] ?? '' }}</textarea>
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

@endsection
@section('script')
    <script src="/assets/vendor/ios7-switch/ios7-switch.js"></script>
    <script src="/assets/vendor/fuelux/js/spinner.js"></script>
    <script>
        $(document).ready(function () {
            $('#user_register_mode').change(function () {
                if($(this).val() == 'active'){
                    $('#user_register_active_email').hide();
                }else{
                    $('#user_register_active_email').show();
                }
            })
        })
    </script>
@endsection
