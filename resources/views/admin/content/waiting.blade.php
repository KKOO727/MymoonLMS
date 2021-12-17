@extends('admin.newlayout.layout')
@section('title')
    Course verification List
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form>
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" value="{!! request()->get('fsdate') ?? '' !!}" id="fsdate" class="text-center form-control" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{!! request()->get('fdate') ?? '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" value="{!! request()->get('lsdate') ?? '' !!}" id="lsdate" class="text-center form-control" name="lsdate" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! request()->get('ldate') ?? '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="user" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_users') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(!empty(request()->get('user')) and request()->get('user') == $user->id) selected @endif>{{ $user->name ?? $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="cat" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{ trans('admin.all_categories') }}</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}" @if(!empty(request()->get('cat')) and request()->get('cat') == $cat->id) selected @endif>{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 h-15"></div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control text-center" name="id" value="{!! request()->get('id') ?? '' !!}" placeholder="Item Code">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" class="form-control text-center" name="title" value="{!! request()->get('title') ?? '' !!}" placeholder="Course Title">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="order" class="form-control">
                                <option value="">{{ trans('admin.filter_type') }}</option>
                                <option value="sella" @if(!empty(request()->get('order')) and request()->get('order') == 'sella') selected @endif>{{ trans('admin.sales') }}-{{ trans('admin.ascending') }}</option>
                                <option value="selld" @if(!empty(request()->get('order')) and request()->get('order') == 'selld') selected @endif>{{ trans('admin.sales') }}-{{ trans('admin.descending') }}</option>
                                <option value="suma" @if(!empty(request()->get('order')) and request()->get('order') == 'suma') selected @endif>{{ trans('admin.sales_amount') }}-{{ trans('admin.ascending') }}</option>
                                <option value="sumd" @if(!empty(request()->get('order')) and request()->get('order') == 'sumd') selected @endif>{{ trans('admin.sales_amount') }}-{{ trans('admin.descending') }}</option>
                                <option value="viewd" @if(!empty(request()->get('order')) and request()->get('order') == 'viewd') selected @endif>{{ trans('admin.views') }}-{{ trans('admin.descending') }}</option>
                                <option value="viewa" @if(!empty(request()->get('order')) and request()->get('order') == 'viewa') selected @endif>{{ trans('admin.views') }}-{{ trans('admin.ascending') }}</option>
                                <option value="pricea" @if(!empty(request()->get('order')) and request()->get('order') == 'pricea') selected @endif>{{ trans('admin.item_price') }}-{{ trans('admin.ascending') }}</option>
                                <option value="priced" @if(!empty(request()->get('order')) and request()->get('order') == 'priced') selected @endif>{{ trans('admin.item_price') }}-{{ trans('admin.descending') }}</option>
                                <option value="datea" @if(!empty(request()->get('order')) and request()->get('order') == 'datea') selected @endif>{{ trans('admin.th_date') }}-{{ trans('admin.ascending') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Apply Filter">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.th_title') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.th_date') }}</th>
                    <th class="text-center">{{ trans('admin.th_vendor') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.sales') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.parts') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.price') }}</th>
                    <th class="text-center">{{ trans('admin.category') }}</th>
                    <th class="text-center">{{ trans('admin.type') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <?php
                    $meta = arrayToList($item->metas, 'option', 'value');
                    if(!isset($item->price)){
                        $item->price=0;
                    }
                    if($item->type =='1'){
                        $item->price=get_user_meta($item->user->id,'meta_hourly_rate','0');
                    }

                    ?>
                    <tr>
                        <td><a href="/product/{{ $item->id }}" target="_blank">{{ $item->title }}</a></td>
                        <td class="text-center" width="150">{{ date('d F Y / H:i',strtotime($item->created_at)) }}</td>
                        <td class="text-center" title="{{ $item->user->username }}"><a href="/admin/user/item/{{ $item->user->id }}">{{ $item->user->name }}</a></td>
                        <td class="text-center">{{ $item->sells_count ?? '0' }}</td>
                        <td class="text-center">{{ $item->partsactive_count ?? '0' }}</td>
                        <td class="text-center">{{ currency($item->price) ?? 'Free' }}</td>
                        <td class="text-center">{{ !empty($item->category) ? $item->category->title : '' }}</td>
                        <td class="text-center">
                            @if($item->type==1)
                                <b class="c-g">{{ trans('One To One') }}</b>
                            @elseif($item->type==2)
                                <b class="c-o">Group Course</b>
                            @elseif($item->type==3)
                                <b class="c-o">Video Course</b>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->parts_request_count > 0)
                                <b class="c-o">{{ trans('admin.pending') }}</b>
                            @endif
                            @if($item->mode == 'request')
                                <b class="c-r">{{ trans('admin.review_request') }}</b>
                            @elseif($item->mode == 'delete')
                                <b class="c-r">{{ trans('admin.unpublish_request') }}</b>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/admin/notification/new?recipent_type=userone&uid={{ $item->user->id }}" title="Send notification to user"><i class="fas fa-bell" aria-hidden="true"></i></a>
                            <a href="/admin/content/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/content/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center">
            {!! $lists->appends($_GET)->links('pagination.default') !!}
        </div>
    </section>

@endsection
