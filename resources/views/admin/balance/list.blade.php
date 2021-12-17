@extends('admin.newlayout.layout',['breadcom'=>['Accounting','Financial Documents']])
@section('title')
    {{ trans('admin.financial_documents') }}
@endsection
@section('page')
    <a href="/admin/balance/list/excel" class="btn btn-primary">Export as xls</a>
    <div class="h-10"></div>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center" width="170">{{ trans('admin.th_date') }}</th>
                    <th class="text-center">{{ trans('admin.th_title') }}</th>
                    <th class="text-center">{{ trans('admin.document_type') }}</th>
                    <th class="text-center">{{ trans('admin.amount') }}</th>
                    <td class="text-center">{{ trans('admin.username') }}</td>
                    <th class="text-center">{{ trans('admin.description') }}</th>
                    <td class="text-center">{{ trans('admin.th_controls') }}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td class="text-center" width="170">{{ date('d F Y / H:i',$item->created_at) }}</td>
                        <td class="text-center">{{ $item->title }}</td>
                        <td class="text-center">
                                <span class="f-w-b color-green">{{ $item->type }}</span>
                        </td>
                        <td class="text-center">

                                <span class="f-w-b color-green">{{ $item->price }}</span>
                        </td>

                        <td class="text-center">
                            @if (!empty($item->user))
                                <a href="/admin/user/edit/{{ $item->user->id }}" title="{{ $item->user->name }}">
                                    {{ !empty($item->user->name) ? $item->user->name : 'Fund' }}
                                </a>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->description }}</td>
                        <td class="text-center">
                            <a href="/admin/balance/print/{{ $item->id }}" target="_blank" title="Print Document"><i class="fa fa-print" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

