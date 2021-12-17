@extends('admin.newlayout.layout',['breadcom'=>['Users List']])
@section('title')
    {{ trans('admin.users_list') }}
@endsection
@section('page')
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">{{ trans('admin.list') }} {{ $category->title ?? 'Users' }}</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.real_name') }}</th>
                    <th class="text-center">{{ trans('admin.reg_date') }}</th>
                    <th class="text-center">{{ trans('admin.courses') }}</th>
                    <th class="text-center">{{ trans('admin.purchases') }}</th>
                    <th class="text-center">{{ trans('admin.sales') }}</th>
                    <th class="text-center">{{ trans('admin.user_group') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                    <th class="text-center">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <th class="text-center">{{ $user->username }}</th>
                        <th class="text-center">{{ $user->name }}</th>
                        <th class="text-center">{{ date('d F Y / H:i',$user->created_at) }}</th>
                        <th class="text-center"><a href="/admin/content/user/{{ $user->id }}">{{ count($user->contents) }}</a></th>
                        <th class="text-center"><a href="/admin/sell/buyer/{{ $user->id }}">{{ count($user->buys) }}</a></th>
                        <th class="text-center"><a href="/admin/sell/user/{{ $user->id }}">{{ count($user->sells) }}</a></th>
                        <th class="text-center"><a href="/admin/user/incategory/{{$user->category->id}}">{{$user->category->title}}</a></th>
                        <th class="text-center">
                            @if($user->mode == 'active')
                                <i class="fa fa-check c-g" aria-hidden="true" title="Active"></i>
                            @else
                                <i class="fa fa-times c-r" aria-hidden="true" title="Banned"></i>
                            @endif
                        </th>
                        <th class="text-center">
                            <a href="/admin/user/item/{{ $user->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection



