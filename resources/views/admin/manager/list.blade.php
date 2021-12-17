@extends('admin.newlayout.layout',['breadcom'=>['Employees','List']])
@section('title')
    {{ trans('admin.employees_list') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.real_name') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.reg_date') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.last_login') }}</th>
                    <th class="text-center">{{ trans('admin.permissions') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                    <th class="text-center">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <?php $meta = arrayToList($user->usermetas->toArray(), 'option', 'value'); ?>
                    <tr>
                        <td class="text-center">{{ $user->username }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center" width="220">@if(is_numeric($user->created_at)) {{ date('d F Y : H:i',strtotime($user->created_at)) }} @endif</td>
                        <td class="text-center" width="220"></td>
                        <td class="text-center">@if(!empty($meta['capatibility'])){{ returnCaptibiliy($meta['capatibility']) }}@endif</td>
                        <td class="text-center">
                            @if($user->mode == 'active')
                                <i class="fa fa-check c-g" aria-hidden="true" title="Active"></i>
                            @else
                                <i class="fa fa-times c-r" aria-hidden="true" title="Banned"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/admin/manager/item/{{ $user->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/user/delete/{{ $user->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection

