@extends('admin.newlayout.layout',['breadcom'=>['Courses','Latest Courses']])
@section('title')
    {{ trans('admin.course_list') }}
@endsection
@section('page')

    <section class="card">
        <header class="card-header">
            <h2 class="card-title">{{ trans('admin.filter_items') }}</h2>
        </header>
        <div class="card-body">
            <form>
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" class="form-control text-center" id="fdate" name="fdate" value="{!! request()->get('fdate') ?? '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="ldate" name="ldate" class="form-control text-center" value="{!! request()->get('ldate') ?? '' !!}">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control text-center" name="id" placeholder="Item Code" value="{!! request()->get('id') ?? '' !!}">
                        </div>
                    </div>
                    <div class="col-md-4">
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
    <a href="/admin/content/list/excel?{!! http_build_query(Request()->all()) !!}{{ !empty($mode) ? '&mode='.$mode : '' }}" class="btn btn-primary">Export as xls</a>
    <div class="h-10"></div>
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-none" id="datatable-details">
                    <thead>
                    <tr>
                        <th class="text-center" width="30">#</th>
                        <th>{{ trans('admin.th_title') }}</th>
                        <th class="text-center" width="150">{{ trans('admin.th_date') }}</th>
                        <th class="text-center">{{ trans('admin.th_vendor') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.sales') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.parts') }}</th>
                        <th class="text-center">{{ trans('admin.income') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.views') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.item_price') }}</th>
                        <th class="text-center">{{ trans('admin.category') }}</th>
                        <th class="text-center">{{ trans('admin.type') }}</th>
                        <th class="text-center">{{ trans('admin.spend_time') }}</th>
                        <th class="text-center">{{ trans('admin.top_viewer') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $item)
                        @php $meta = arrayToList($item->metas,'option','value'); @endphp
                        <tr>
                            <td class="text-center">{!! $item->id  !!}</td>
                            <td><a href="/product/{{ $item->id }}" target="_blank">{{ $item->title }}</a></td>
                            <td class="text-center" width="150">{{ date('d F Y / H:i',strtotime($item->created_at)) }}</td>
                            <td class="text-center" title="{{ $item->user->username }}"><a href="/admin/user/item/{{ $item->user->id }}">{{ $item->user->name }}</a></td>
                            <td class="text-center">{{ $item->sells_count ?? '0' }}</td>
                            <td class="text-center">{{ $item->partsactive_count ?? '0' }}</td>
                            <td class="text-center">{{ $item->transactions->sum('price') }}<br>{{ currencySign() }}</td>
                            <td class="text-center">{{ $item->view ?? '0' }}</td>
                            <td class="text-center">{{ $meta['price'] ?? 'Free' }}</td>
                            <td class="text-center">{{ !empty($item->category) ? $item->category->title : '' }}</td>
                            <td class="text-center">
                                @if($item->private==1)
                                    <b class="c-g">{{ trans('admin.exclusive') }}</b>
                                @else
                                    <b class="c-o">{{ trans('admin.open') }}</b>
                                @endif
                            </td>
                            <td class="text-center">{!! productSpendTime($item->id) !!}</td>
                            <td class="text-center">
                                @if(productTopViewer($item->id))
                                    {!! productTopViewer($item->id) !!}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->mode == 'publish')
                                    <b class="c-g">{{ trans('admin.published') }}</b>
                                @elseif($item->mode == 'draft')
                                    <b class="c-o">{{ trans('admin.draft') }}</b>
                                @elseif($item->mode == 'request')
                                    <span class="c-r">{{ trans('admin.pending') }}</span>
                                @elseif($item->mode == 'delete')
                                    <span class="c-r">{{ trans('admin.unpublish_request') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/admin/notification/new?recipent_type=userone&uid={{ $item->user->id }}" title="Send notification to user"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                <a href="/admin/ticket/new?uid={{ $item->user->id }}&title=Course {{ $item->title }}" title="Send support ticket to user"><i class="fa fa-life-ring" aria-hidden="true"></i></a>
                                <a href="/admin/content/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/content/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <a href="/admin/content/usage/{{ $item->id }}" title="Spend Time"><i class="fa fa-clock" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            {!! $lists->appends($_GET)->links('pagination.default') !!}
        </div>
    </section>
@endsection
