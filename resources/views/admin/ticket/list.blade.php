@extends('admin.newlayout.layout',['breadcom'=>['Support','Tickets']])
@section('title')
    {{ trans('admin.tickets_list') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
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
                            @if($item->updated_at>0)
                                <td class="text-center">{{ date('d F Y : H:i',$item->updated_at) }}</td>
                            @else
                                <td class="text-center">{{ date('d F Y : H:i',$item->created_at) }}</td>
                            @endif
                            <td class="text-center">
                                <a title="{{ $item->user->name }}" href="/profile/{{ $item->user->id }}">{{ $item->user->username }}</a>
                            </td>
                            <td class="text-center">
                                @if($item->users != null)
                                    @foreach($item->users as $u)
                                        <a title="{{ $u->user->name }}" href="/profile/{{ $u->user->id }}">{{ $u->user->username }}</a>
                                        <br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{ $item->category->title }}</td>
                            <td class="text-center">
                                @if($item->mode == 'open')
                                    <b class="f-w-b">{{ trans('admin.waiting') }}</b>
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
        </div>
        <div class="card-footer text-center">
            {!! $lists->appends($_GET)->links('pagination.default') !!}
        </div>
    </section>
@endsection


