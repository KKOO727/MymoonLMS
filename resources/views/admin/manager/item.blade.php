@extends('admin.newlayout.layout',['breadcom'=>['Employees','Edit Employee',$user->name]])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.employees') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="#main" class="nav-link active" data-toggle="tab"> {{ trans('admin.general') }} </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile" class="nav-link" data-toggle="tab">{{ trans('admin.profile') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#information" class="nav-link" data-toggle="tab">{{ trans('admin.extra_info') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#capatibility" class="nav-link" data-toggle="tab">{{ trans('admin.permissions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="#document" class="nav-link" data-toggle="tab">{{ trans('admin.identity') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="main" class="tab-pane active">
                        <form action="/admin/user/edit/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.real_name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.username') }}</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ $user->username }}" id="inputReadOnly" class="form-control" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.email') }}</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{ $user->email }}" id="inputReadOnly" class="form-control text-left" dir="ltr" readonly="readonly">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.th_status') }}</label>
                                <div class="col-md-6">
                                    <select name="mode" class="form-control populate">
                                        <option value="active" {{ $user->mode=='active' ? 'selected="selected"':'' }}>{{ trans('admin.active') }}</option>
                                        <option value="deactive" {{ $user->mode=='deactive' ? 'selected="selected"':'' }}>{{ trans('admin.banned') }}</option>
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
                    <div id="profile" class="tab-pane">
                        <form action="/admin/user/editprofile/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.profile_pic') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="avatar" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="avatar" class="form-control" type="text" name="avatar" dir="ltr" value="{{ $meta['avatar'] ?? '' }}">
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.birthday') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="date" name="birthday" id="birthday" value="{{ $meta['birthday'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                        <span class="input-group-append fdatebtn" id="fdatebtn">
                                            <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.bank_name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="bank_name" value="{{ $meta['bank_name'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.account_number') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="bank_account" value="{{ $meta['bank_account'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.creditcard_number') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="bank_card" value="{{ $meta['bank_card'] ?? '' }}" class="form-control text-center" id="inputDefault">
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
                    <div id="information" class="tab-pane">
                        <form action="/admin/user/editprofile/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.passport_id') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="meli_code" value="{{ $meta['meli_code'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.mobile_number') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="mobile" value="{{ $meta['mobile'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.Telephone') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="phone" value="{{ $meta['phone'] ?? '' }}" class="form-control text-center" id="inputDefault">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.address') }}</label>
                                <div class="col-md-9">
                                    <input type="text" name="address" value="{{ $meta['address'] ?? '' }}" class="form-control text-center" id="inputDefault">
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
                    <div id="capatibility" class="tab-pane">
                        <form action="/admin/manager/capatibility/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="report" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('report',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.reports_breadcom') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="user" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('user',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.users') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="channel" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('channel',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.channels') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="quizzes" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('quizzes',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.quizzes') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="content" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('content',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.courses') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="manager" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('manager',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.employees') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="request" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('request',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.course_requests') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="ads" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('ads',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.advertising') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="blog" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('blog',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.blog_articles') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="balance" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('balance',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.financial') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="buysell" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('buysell',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.sales') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="ticket" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('ticket',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.support') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="notification" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('notification',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.notifications') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="email" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('email',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.emails') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="discount" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('discount',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.promotions_discounts') }}</label>
                                </label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="capatibility[]" value="setting" class="custom-switch-input" @if(!empty($meta['capatibility'])) {{ checkedInArray('setting',$meta['capatibility'],true) }} @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.settings') }}</label>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div id="document" class="tab-pane">
                        <form action="/admin/user/editprofile/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.identity_scan') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="upload_certificate" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="upload_certificate" class="form-control" type="text" name="upload_certificate" dir="ltr" value="{{ $meta['upload_certificate'] ?? '' }}" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_certificate">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.contract') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="upload_deal" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="upload_deal" class="form-control" type="text" name="upload_deal" dir="ltr" value="{{ $meta['upload_deal'] ?? '' }}" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_deal">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.payslip') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="upload_payroll" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="upload_payroll" class="form-control" type="text" name="upload_payroll" dir="ltr" value="{{ $meta['upload_payroll'] ?? '' }}" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_payroll">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                        </div>
                                    </div>
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

@section('script')
    <script>
        $(function () {
            $('select[name="mode"]').change(function () {
                if ($(this).val() == 'block') {
                    $('.birthday-group').removeClass('hidden');
                } else {
                    $('.birthday-group').addClass('hidden');
                }
            })
        })
    </script>
@endsection


