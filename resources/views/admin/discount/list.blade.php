@extends('admin.newlayout.layout',['breadcom'=>['Giftcards','Promotions']])
@section('title')
    {{ trans('admin.discounts') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.th_title') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.created_date') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.expire_date') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.type') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.amount') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td class="text-center" width="150">{{ date('d F Y / H:i',$item->created_at) }}</td>
                        <td class="text-center" width="150" @if($item->expire_at < time()) title="Expired" style="color:red" @endif >{{ date('d F Y',$item->expire_at) }}</td>
                        <td class="text-center" width="150">
                            @if($item->type == 'gift')
                                {{ 'Giftcard' }}
                            @elseif($item->type == 'off')
                                {{ 'Discount Card' }}
                            @endif
                        </td>
                        <td class="text-center" width="150">
                            @if($item->type == 'gift')
                                {{ $item->off ?? '' }} {{ trans('admin.cur_dollar') }}
                            @elseif($item->type == 'off')
                                {{ $item->off ?? '' }} %
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            @if($item->mode == 'publish')
                                <i class="fa fa-check c-g" aria-hidden="true" title="Active"></i>
                            @elseif($item->recipent_type == 'darft')
                                <i class="fa fa-times c-r" aria-hidden="true" title="Disabled"></i>
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            <a href="/admin/discount/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/discount/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection



