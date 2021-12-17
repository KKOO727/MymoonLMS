@extends('admin.newlayout.layout',['breadcom'=>['Sales','Sales List']])
@section('title')
    {{ trans('admin.sales_list') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form>
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="fsdate" class="text-center form-control" value="{{ request()->get('fsdate') ?? '' }}" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{{ request()->get('fdate') ?? '' }}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="lsdate" class="text-center form-control" name="lsdate" value="{{ request()->get('lsdate') ?? '' }}" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{{ request()->get('ldate') ?? '' }}">
                            <span class="input-group-append ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="user" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_vendors') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(!empty(request()->get('user')) and request()->get('user') == $user->id) selected @endif>{{ $user->name ?? $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="buyer" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_customers') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(!empty(request()->get('buyer')) and request()->get('buyer') == $user->id) selected @endif>{{ $user->name ?? $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 h-15"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="content" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_courses') }}</option>
                                @foreach($contents as $content)
                                    <option value="{{ $content->id }}" @if(!empty(request()->get('content')) and request()->get('content') == $content->id) selected @endif>{{ $content->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="type" class="form-control">
                                <option value="">{{ trans('admin.all_types') }}</option>
                                <option value="download" @if(!empty(request()->get('type')) and request()->get('type') == 'download') selected @endif>{{ trans('admin.download') }}</option>
                                <option value="post" @if(!empty(request()->get('type')) and request()->get('type') == 'post') selected @endif>{{ trans('admin.postal') }}</option>
                                <option value="success" @if(!empty(request()->get('type')) and request()->get('type') == 'success') selected @endif>{{ trans('admin.postal_successful') }}</option>
                                <option value="fail" @if(!empty(request()->get('type')) and request()->get('type') == 'fail') selected @endif>{{ trans('admin.postal_failed') }}</option>
                                <option value="wait" @if(!empty(request()->get('type')) and request()->get('type') == 'wait') selected @endif>{{ trans('admin.postal_waiting') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Show Results">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <header class="card-header">
            <h4 class="card-title">{{ trans('admin.sales_list') }}</h4>
        </header>
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.course_title') }}</th>
                    <th class="text-center">{{ trans('admin.th_vendor') }}</th>
                    <th class="text-center">{{ trans('admin.th_customer') }}</th>
                    <th class="text-center">{{ trans('admin.amount') }}</th>
                    <th class="text-center">{{ trans('admin.th_date') }}</th>
                    <th class="text-center">{{ trans('admin.sales_type') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td>
                            @if (!empty($item->content))
                                <a href="/product/{{ $item->content->id }}">{{ $item->content->title }}</a>
                            @endif
                        </td>
                        <td class="text-center" title="{{ !empty($item->user) ? $item->user->name : '' }}"><a href="/profile/{{ !empty($item->user) ? $item->user->id : '' }}">{{ !empty($item->user) ? $item->user->username : '' }}</a></td>
                        <td class="text-center" title="{{ !empty($item->buyer) ? $item->buyer->name : '' }}"><a href="/profile/{{ !empty($item->buyer) ? $item->buyer->id : '' }}">{{ !empty($item->buyer) ? $item->buyer->username : '' }}</a></td>
                        <td class="text-center">{{ !empty($item->transaction) ? $item->transaction->price : '' }} {{ trans('admin.cur_dollar') }}</td>
                        <td class="text-center">{{ date('d F Y : H:i',$item->created_at) }}</td>
                        <td class="text-center">
                            @if($item->type == 'download')
                                <b class="c-g">{{ trans('admin.download') }}</b>
                            @else
                                <b class="c-b" title="Shipping Tracing Code {{ $item->post_code }}">{{ trans('admin.postal') }}</b>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->mode == 'pay')
                                @if($item->type == 'download')
                                    <b class="c-g">{{ trans('admin.paid_successful') }}</b>
                                @else
                                    @if($item->post_feedback == 1)
                                        <b class="c-g">{{ trans('admin.paid_successful') }}</b>
                                    @elseif($item->post_feedback == 2 and $item->post_feedback == 2)
                                        <b class="c-r">{{ trans('admin.paid_failed') }}</b>
                                    @else
                                        <b class="c-o">{{ trans('admin.paid_waiting') }}</b>
                                    @endif
                                @endif
                            @elseif($item->mode == 'get')
                                <b class="c-b">{{ trans('admin.delivered') }}</b>
                            @else
                                <b class="c-o">{{ trans('admin.waiting_payment') }}</b>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
