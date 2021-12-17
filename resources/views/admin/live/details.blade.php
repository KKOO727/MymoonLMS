@extends('admin.newlayout.layout')
@section('title','"'.$live->title.'" User List')
@section('page')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                    <tr>
                        <th>{!! trans('admin.username') !!}</th>
                        <th>{!! trans('admin.email') !!}</th>
                        <th>Purchase Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                    <tr>
                        <td>{!! $item->username ?? '' !!}</td>
                        <td>{!! $item->email ?? '' !!}</td>
                        <td>{!! date('Y/m/d H:i',$item->created_at) ?? '' !!}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($list->hasPages())
        <div class="card-footer">
            {!! $list->appends($_GET)->links('pagination.default') !!}
        </div>
        @endif
    </div>
@stop
