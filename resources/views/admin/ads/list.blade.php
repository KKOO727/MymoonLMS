@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Plans']])
@section('title')
    {{ trans('admin.ads_plans') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">{{ trans('admin.th_title') }}</th>
                    <th class="text-center">{{ trans('admin.description') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.price') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.duration') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $list)
                    <tr>
                        <th class="text-center">{{ $list->title }}</th>
                        <th class="text-center">{!! $list->description !!}</th>
                        <th class="text-center number-green" width="100" @if($list->price < 1000) style="color:red !important;" @endif dir="ltr">{{ $list->price }}</th>
                        <th class="text-center number-green" width="100" @if($list->day < 30) style="color:red !important;" @endif dir="ltr">{{ $list->day }}</th>
                        <th class="text-center">
                            @if($list->mode == 'publish')
                                <span class="color-green">{{ trans('admin.active') }}</span>
                            @elseif($list->mode == 'draft')
                                <span class="color-orange">{{ trans('admin.disabled') }}</span>
                            @endif
                        </th>
                        <th class="text-center">
                            <a href="/admin/ads/plan/edit/{{ $list->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/ads/plan/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
