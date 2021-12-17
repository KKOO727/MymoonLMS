@extends('admin.newlayout.layout',['breadcom'=>['Users','Event','Admin Panel']])
@include('admin.layout.modals')
@section('title')
    Events List
@endsection
@section('page')
    <section class="card">
        <header class="card-header">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">{{ trans('admin.filter_items') }}</h2>
        </header>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="fsdate" class="text-center form-control" value="{{ request()->get('fsdate') ?? '' }}" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{{ request()->get('fdate') ?? '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text fdatebtn" id="fdatebtn"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="lsdate" class="text-center form-control" name="lsdate" value="{{ request()->get('lsdate') ?? '' }}" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{{ request()->get('ldate') ?? '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text ldatebtn" id="ldatebtn"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Filter Items">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-none m-b-0" id="datatable-details">
                    <thead>
                    <tr>
                        <th class="text-center">{{ trans('admin.th_date') }}</th>
                        <th class="text-center">IP</th>
                        <th class="text-center">Type</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td class="text-center">{!! $item->created_at !!}</td>
                            <td class="text-center">{!! $item->ip !!}</td>
                            <td class="text-center">{!! $item->type !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($list->hasPages())
            <div class="card-footer">
                {!! $list->links() !!}
            </div>
        @endif
    </section>
@endsection


