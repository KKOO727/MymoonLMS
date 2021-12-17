@extends('admin.newlayout.layout',['breadcom'=>['Courses','Support']])
@section('title')
    {{ trans('admin.courses_support') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.text') }}</th>
                    <th class="text-center" width="120">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.course') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $item)
                    <tr>
                        <td>{!! $item->comment !!}</td>
                        <td class="text-center"><a target="_blank" href="javascript:void(0);">{{ !empty($item->user) ? $item->user->name : '' }}</a></td>
                        <td class="text-center">
                            @if($item->mode == 'publish')
                                <span class="c-g f-w-b">{{ trans('admin.published') }}</span>
                            @else
                                <span class="c-o f-w-b">{{ trans('admin.pending') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!empty($item->content))
                                <a href="/product/{{ $item->content->id }}" target="_blank">{{ $item->content->title }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/admin/content/support/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/content/support/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            @if($item->mode == 'publish')
                                <a href="/admin/content/support/view/draft/{{ $item->id }}/" title="Add to pending list (Hide)"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            @else
                                <a href="/admin/content/support/view/publish/{{ $item->id }}/" title="Confirm and send to vendor"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
