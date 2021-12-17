@extends('admin.newlayout.layout',['breadcom'=>['Support','Closed Tickets']])
@section('title')
    {{ trans('admin.closed_tickets_list') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form>
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" value="{!! request()->get('fsdate') ?? '' !!}" id="fsdate" class="text-center form-control" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{!! request()->get('fdate') ?? '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="lsdate" value="{!! request()->get('lsdate') ?? '' !!}" class="text-center form-control" name="lsdate" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! request()->get('ldate') ?? '' !!}">
                            <span class="input-group-addon ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="user" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_users') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(!empty(request()->get('user')) and request()->get('user') == $user->id) selected @endif>{{ $user->username ?? $user->name }}</option>
                                @endforeach
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
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.th_title') }}</th>
                    <th class="text-center">{{ trans('admin.created_date') }}</th>
                    <th class="text-center">{{ trans('admin.last_update') }}</th>
                    <th class="text-center">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.invited_users') }}</th>
                    <th class="text-center">{{ trans('admin.department') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                    <th class="text-center">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td><a href="/admin/ticket/reply/{{ $item->id }}">{{ $item->title }}</a></td>
                        <td class="text-center">{{ date('d F Y : H:i',$item->created_at) }}</td>
                        <td class="text-center">{{ date('d F Y : H:i',$item->updated_at) }}</td>
                        <td class="text-center">
                            @if (!empty($item->user))
                                <a title="{{ $item->user->name }}" href="/profile/{{ $item->user->id }}">{{ $item->user->username }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->users != null)
                                @foreach($item->users as $u)
                                    <a title="{{ $u->name }}" href="/profile/{{ $u->id }}">{{ $u->username }}</a>
                                    <br>
                                @endforeach
                            @endif
                        </td>
                        <td class="text-center">{{ !empty($item->category) ? $item->category->title : '' }}</td>
                        <td class="text-center">
                            @if($item->mode == 'open')
                                <b class="f-w-b">{{ trans('admin.pending') }}</b>
                            @elseif($item->mode == 'admin')
                                <b class="c-g">{{ trans('admin.replied') }}</b>
                            @else
                                <b class="c-r">{{ trans('admin.closed') }}</b>
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            <a href="/admin/ticket/user/{{ $item->id }}" title="Add user to conversation"><i class="fa fa-user" aria-hidden="true"></i></a>
                            <a href="/admin/ticket/reply/{{ $item->id }}" title="Reply"><i class="fa fa-reply" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/ticket/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
