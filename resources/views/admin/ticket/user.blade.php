@extends('admin.newlayout.layout',['breadcom'=>['Support',$ticket->title,'Users List']])
@section('page')

    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">{{ trans('admin.add_user_conversation') }}</h2>
        </header>
        <div class="panel-body">
            <form method="post" action="/admin/ticket/user/store">
                {{ csrf_field() }}
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div class="col-md-6">
                    <div class="form-group">
                        <select name="user_id" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('admin.all_users') }}</option>
                            @foreach($userss as $user)
                                <option value="{{ $user->id }}" @if(!empty(request()->get('user')) and request()->get('user') == $user->id) selected @endif>{{ $user->username ?? $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="submit" class="text-center btn btn-primary w-100" value="Add User To Conversation">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <a href="/admin/ticket/reply/{{ $ticket->id }}" class="text-center btn btn-success w-100">{{ trans('admin.go_to_ticket') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">{{ trans('admin.users_list') }} {{ $ticket->title }}</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.username') }}</th>
                    <th class="text-center" width="250">{{ trans('admin.real_name') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="/profile/{{ $user->user->id }}" title="{{ $user->user->name }}">{{ $user->user->username }}</a></td>
                        <td class="text-center">{{ $user->user->name }}</td>
                        <td class="text-center" width="50">
                            <a href="#" data-href="/admin/ticket/user/delete/{{ $user->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection


