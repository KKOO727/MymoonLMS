@extends('admin.newlayout.layout',['breadcom'=>['Settings','Notifications']])
@section('title')
    {{ trans('admin.notifications') }}
@endsection
@section('page')

    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#user-tab" data-toggle="tab"> {{ trans('admin.users') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#content-tab" data-toggle="tab"> {{ trans('admin.courses') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#request-tab" data-toggle="tab"> {{ trans('admin.course_requests') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#support-tab" data-toggle="tab"> {{ trans('admin.support') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#balance-tab" data-toggle="tab"> {{ trans('admin.financial_documents') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#buy-tab" data-toggle="tab"> {{ trans('admin.sell_purchase') }}  </a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="user-tab" class="tab-pane active">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.user_gp_change') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_change_group" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_change_group']) and $temp->id == $_setting['notification_template_change_group']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_badge') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_get_medal" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_get_medal']) and $temp->id == $_setting['notification_template_get_medal']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.remove_badge') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_delete_medal" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_delete_medal']) and $temp->id == $_setting['notification_template_delete_medal']) selected @endif>{{ $temp->title }}</option>
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
                <div id="content-tab" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.submit_course') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_pre_publish" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_pre_publish']) and $temp->id == $_setting['notification_template_content_pre_publish']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.approve_course') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_publish" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_publish']) and $temp->id == $_setting['notification_template_content_publish']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.course_changes') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_change" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_change']) and $temp->id == $_setting['notification_template_content_publish']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.course_reject') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_delete" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_delete']) and $temp->id == $_setting['notification_template_content_delete']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_comment') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_comment_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_comment_new']) and $temp->id == $_setting['notification_template_content_comment_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_support_message') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_content_support_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_content_support_new']) and $temp->id == $_setting['notification_template_content_support_new']) selected @endif>{{ $temp->title }}</option>
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
                <div id="request-tab" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.request_submit') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_request_get" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_request_get']) and $temp->id == $_setting['notification_template_request_get']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.request_publish') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_request_publish" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_request_publish']) and $temp->id == $_setting['notification_template_request_publish']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.request_reject') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_request_draft" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_request_draft']) and $temp->id == $_setting['notification_template_request_draft']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.course_production_request') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_request_req" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_request_req']) and $temp->id == $_setting['notification_template_request_req']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.request_followed') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_request_follow" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_request_follow']) and $temp->id == $_setting['notification_template_request_follow']) selected @endif>{{ $temp->title }}</option>
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
                <div id="support-tab" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_support_ticket') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_ticket_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_ticket_new']) and $temp->id == $_setting['notification_template_ticket_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_reply') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_ticket_reply" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_ticket_reply']) and $temp->id == $_setting['notification_template_ticket_reply']) selected @endif>{{ $temp->title }}</option>
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
                <div id="balance-tab" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_financial_doc') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_withdraw_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_withdraw_new']) and $temp->id == $_setting['notification_template_withdraw_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.withdraw_doc') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_withdraw_pay" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_withdraw_pay']) and $temp->id == $_setting['notification_template_withdraw_pay']) selected @endif>{{ $temp->title }}</option>
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
                <div id="buy-tab" class="tab-pane">
                    <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_purchase') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_buy_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_buy_new']) and $temp->id == $_setting['notification_template_buy_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_sale') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_sell_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_sell_new']) and $temp->id == $_setting['notification_template_sell_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.postal_sale_feedback') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_feedback_new" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_feedback_new']) and $temp->id == $_setting['notification_template_feedback_new']) selected @endif>{{ $temp->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.postal_sent') }}</label>
                            <div class="col-md-7">
                                <select id="template" name="notification_template_buy_post_withdraw" class="form-control">
                                    <option value=""></option>
                                    @foreach($template as $temp)
                                        <option value="{{ $temp->id }}" @if(isset($_setting['notification_template_buy_post_withdraw']) and $temp->id == $_setting['notification_template_buy_post_withdraw']) selected @endif>{{ $temp->title }}</option>
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
            </div>
        </div>

    </div>
@endsection
