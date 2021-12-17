@extends('admin.newlayout.layout',['breadcom'=>['Users','Rating','Admin Panel']])
@include('admin.layout.modals')
@section('title')
    {{ trans('admin.users_list') }}
@endsection
@section('page')

<?php $base_url= baseURL(); ?>
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
                        <div class="form-group">
                            <select name="order" class="form-control">
                                <option value="">{{ trans('admin.filter_type') }}</option>
                                <option value="sella" @if(!empty(request()->get('order')) and request()->get('order') == 'sella') selected @endif>{{ trans('admin.sales') }}-{{ trans('admin.ascending') }}</option>
                                <option value="selld" @if(!empty(request()->get('order')) and request()->get('order') == 'selld') selected @endif>{{ trans('admin.sales') }}-{{ trans('admin.descending') }}</option>
                                <option value="buya" @if(!empty(request()->get('order')) and request()->get('order') == 'buya') selected @endif>{{ trans('admin.purchases') }}-{{ trans('admin.ascending') }}</option>
                                <option value="buyd" @if(!empty(request()->get('order')) and request()->get('order') == 'buyd') selected @endif>{{ trans('admin.purchases') }}-{{ trans('admin.descending') }}</option>
                                <option value="contenta" @if(!empty(request()->get('order')) and request()->get('order') == 'contenta') selected @endif>{{ trans('admin.badges_tab_courses_count') }}-{{ trans('admin.ascending') }}</option>
                                <option value="contentd" @if(!empty(request()->get('order')) and request()->get('order') == 'contentd') selected @endif>{{ trans('admin.badges_tab_courses_count') }}-{{ trans('admin.descending') }}</option>
                                <option value="datea" @if(!empty(request()->get('order')) and request()->get('order') == 'datea') selected @endif>{{ trans('admin.th_date') }}-{{ trans('admin.ascending') }}</option>
                                <option value="seller" @if(!empty(request()->get('order')) and request()->get('order') == 'seller') selected @endif>{{ trans('admin.sell_purchase') }}</option>
                                <option value="disabled" @if(!empty(request()->get('order')) and request()->get('order') == 'disabled') selected @endif>{{ trans('admin.disabled_users') }}</option>
                            </select>
                        </div>
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
        <header class="card-header">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>
            <h2 class="panel-title">{{ trans('admin.users_list') }}</h2>
        </header>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-none m-b-0" id="datatable-details">
                    <thead>
                    <tr>
                        <th class="text-center">{{ trans('admin.username') }}</th>
                        <th class="text-center">{{ trans('admin.real_name') }}</th>
                        <th class="text-center">{{ trans('admin.reg_date') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.income') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.account_balance') }}</th>
                        <th class="text-center">{{ trans('admin.badges_tab_courses_count') }}</th>
                        <th class="text-center">{{ trans('admin.purchases') }}</th>
                        <th class="text-center">{{ trans('admin.sales') }}</th>
                        <th class="text-center">{{ trans('admin.user_groups') }}</th>
                        <th class="text-center">{{ trans('admin.th_status') }}</th>
                        <th class="text-center">{{ trans('admin.th_controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th class="text-center"><a @if($user['vendor'] == 1) style="color: green;font-weight: bold;" title="vendor" @endif target="_blank" href="{{ $base_url}}/profile/{{ $user->id }}">{{ $user->username }}</a></th>
                            <th class="text-center">{{ $user->name }}</th>
                            <th class="text-center">{{ date('d F Y / H:i',strtotime($user->created_at)) }}</th>
                            <th class="text-center number-green" width="100" @if($user->income < 0) style="color:red !important;" @endif dir="ltr">{{ number_format($user->income) }}</th>
                            <th class="text-center number-green" width="100" @if($user->credit < 0) style="color:red !important;" @endif dir="ltr">{{ number_format($user->credit) }}</th>
                            <th class="text-center"><a href="{{ $base_url}}/admin/content/user/{{ $user->id }}">{{ $user->contents_count }}</a></th>
                            <th class="text-center"><a href="{{ $base_url}}/admin/buysell/list/?buyer={{ $user->id }}">{{ $user->buys_count }}</a></th>
                            <th class="text-center"><a href="{{ $base_url}}/admin/buysell/list/?user={{ $user->id }}">{{ $user->sells_count }}</a></th>
                            @if(!empty($user->category->id))
                                <th class="text-center"><a href="{{ $base_url}}/admin/user/incategory/{{$user->category->id}}">{{$user->category->title}}</a></th>
                            @else
                                <td></td>
                            @endif
                            <th class="text-center">
                                @if($user->mode == 'active')
                                    <span class="c-g">{{ trans('admin.active') }}</span>
                                @elseif($user->mode == 'deactive')
                                    <span class="c-o">{{ trans('admin.disabled') }}</span>
                                @elseif($user->mode == 'block')
                                    <span class="c-r">{{ trans('admin.banned') }}</span>
                                @endif
                            </th>
                            <th class="text-center" width="150">
                                <a href="{{ $base_url}}/admin/user/item/{{ $user->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="{{ $base_url}}/admin/user/userlogin/{{ $user->id }}" title="Login as user" target="_blank"><i class="fa fa-user" aria-hidden="true"></i></a>
                                <a href="{{ $base_url}}/admin/user/event/{{ $user->id }}" title="User Events"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                <a href="#" data-href="{{ $base_url}}/admin/user/delete/{{ $user->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete" class="c-r"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection


