@extends('admin.newlayout.layout',['breadcom'=>['Support','Reply Ticket',$ticket->title]])
@section('title')
    {{ trans('admin.reply_ticket') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <a href="/admin/ticket/user/{{ $ticket->id }}" class="btn btn-primary">{{ trans('admin.add_user_conversation') }}</a>
            &nbsp;&nbsp;
            <span>{{ trans('admin.ticket_created_by') }} </span>
            <span><a href="/profile/{{ $ticket->user->id }}" target="_blank">{{ $ticket->user->username }}</a></span>
            <span> {{ trans('admin.and_this_users_invited') }} </span>
            <span>
                @foreach($ticket->users as $tUser)
                    &nbsp;<a href="/profile/{{ $tUser->user->id }}" target="_blank">{{ $tUser->user->username }}</a>&nbsp;
                @endforeach
            </span>
        </div>
    </div>

    @foreach($ticketMsg as $msg)
        <section class="card">
            @if($msg->mode == 'user')
                <header class="card-header">
                    @else
                        <header class="card-header" style="background: #008DEF">
                            @endif
                            <div class="card-header-action" style="float: right;position: relative;right: 10px;">
                                @if($msg->attach != null && $msg->attach != '')
                                    <a href="{!! $msg->attach !!}" target="_blank" class="panel-action custom-reply"><i class="fa fa-paperclip" style="color: gray"></i></a>
                                @endif
                                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                            </div>

                            @if($msg->mode == 'user')
                                <h4 class="card-title">{{ trans('admin.user') }} - {{ $msg->user->name }}</h4>
                            @else
                                <h4 class="card-title" style="color: #fafafa"> {{ trans('admin.support_staff') }} - {{ $msg->user->name }}</h4>
                            @endif
                        </header>
                        <div class="card-body">
                            {!! $msg->msg !!}
                            <hr>
                            <a href="/admin/ticket/reply/{{ $msg->ticket_id }}/edit/{{ $msg->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" title="Delete" data-href="/admin/ticket/reply/delete/{{ $msg->id }}" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            <span class="float-right custom-reply-2">{{ date('d F Y : H:i',$msg->created_at) }}</span>
                        </div>
        </section>
    @endforeach

    <section class="card">
        <header class="card-header">
            <div class="card-header-action">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h4 class="card-title">{{ trans('admin.reply_ticket') }}</h4>

        </header>
        <div class="card-body">
            <form action="/admin/ticket/reply/store/{{ $ticket->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id ?? '' }}">

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="msg" required>{{ $item->msg ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 text-left">{{ trans('admin.attachments') }}</label>
                    <div class="col-md-6">

                        <div class="input-group" style="display: flex">
                            <button type="button" data-input="attach" data-preview="holder" class="lfm_image btn btn-primary">
                                Choose
                            </button>
                            <input id="attach" class="form-control" value="{{ $item->attach ?? '' }}" type="text" name="attach" dir="ltr" >
                            <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="attach">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        @if($ticket->mode == 'open')
                            <a class="btn btn-danger pull-right" href="/admin/ticket/close/{{ $ticket->id }}">{{ trans('admin.close_ticket') }}</a>
                        @else
                            <a class="btn btn-success pull-right" href="/admin/ticket/open/{{ $ticket->id }}">{{ trans('admin.open_ticket') }}</a>
                        @endif
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.send') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
