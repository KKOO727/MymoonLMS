@extends('admin.newlayout.layout',['breadcom'=>['Requests','List']])
@section('title')
    {{ trans('admin.verification_requests') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.request_description') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.th_date') }}</th>
                    <th class="text-center">{{ trans('admin.creator') }}</th>
                    <th class="text-center">{{ trans('admin.channel_title') }}</th>
                    <th class="text-center">{{ trans('admin.documents') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td class="text-center" width="150">{{ date('d F Y / H:i',$item->created_at) }}</td>
                        <td class="text-center" title="{{ $item->user->username }}">{{ $item->user->name }}</td>
                        <td class="text-center" title="{{ $item->channel->title }}">{{ $item->channel->title }}</td>
                        <td class="text-center">@if(isset($item->attach)) {!! '<a target="_blank" href="'.$item->attach.'">Download</a>' !!} @else No data @endif</td>
                        <td class="text-center">
                            @if($item->mode == 'publish')
                                <span class="c-g f-w-b">{{ trans('admin.active') }}</span>
                            @else
                                <span class="c-r f-w-b">{{ trans('admin.disabled') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->mode == 'publish')
                                <a href="/admin/channel/request/draft/{{ $item->id }}" title="Disable Channel"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                            @else
                                <a href="/admin/channel/request/publish/{{ $item->id }}" title="Publish Channel"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                            @endif
                            <a href="#" data-href="/admin/channel/request/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection


