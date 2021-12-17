@extends('admin.newlayout.layout',['breadcom'=>['Notifications','Notifications List']])
@section('title')
    {{ trans('admin.notifications') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.th_title') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.sent_date') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.sender') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.receipts') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td class="text-center" width="150">{{ date('d F Y / H:i',$item->created_at) }}</td>
                        <td class="text-center" title="{{ $item->user->username ?? 'Automatic' }}" width="150">{{ $item->user->name ?? 'Automatic' }}</td>
                        <td class="text-center" width="150">
                            @if($item->recipent_type == 'users')
                                {{ 'Users' }}
                            @elseif($item->recipent_type == 'user')
                                {{ 'Single User' }}
                            @elseif($item->recipent_type == 'category')
                                {{ 'User Group' }}
                            @elseif($item->recipent_type == 'seller')
                                {{ 'Vendors' }}
                            @elseif($item->recipent_type == 'buyer')
                                {{ 'Customers' }}
                            @elseif($item->recipent_type == 'female')
                                {{ 'Females' }}
                            @elseif($item->recipent_type == 'male')
                                {{ 'Males' }}
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            <a href="/admin/notification/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/notification/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection


