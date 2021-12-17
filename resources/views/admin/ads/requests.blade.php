@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Requests']])
@section('title')
    {{ trans('admin.ads_requests') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">{{ trans('admin.plan') }}</th>
                    <th class="text-center">{{ trans('admin.description') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.course_title') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.th_date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $list)
                    <tr>
                        <td class="text-center">{{ !empty($list->plan) ? $list->plan->title : '' }}</td>
                        <td class="text-center">{{ $list->description }}</td>
                        <td class="text-center">{{ !empty($list->user->username) ? $list->user->username : 'Deleted User' }}</td>
                        <td class="text-center">{{ !empty($list->content) ? $list->content->title : '' }}</td>
                        <td class="text-center">
                            @if($list->mode == 'pending')
                                <b class="color-red-i">{{ trans('admin.waiting_payment') }}</b>
                            @else
                                <b class="color-green">{{ trans('admin.paid_successful') }}</b>
                            @endif
                        </td>
                        <td class="text-center">{{ date('d F Y H:i',$list->created_at) }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </section>

@endsection



