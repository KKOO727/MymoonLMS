@extends('admin.newlayout.layout',['breadcom'=>['Requests','Future Courses']])
@section('title')
    {{ trans('admin.future_courses') }}
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
                            <input type="date" id="lsdate" value="{!! request()->get('lsdate') ?? '' !!}" class="text-center form-control" name="lsdate" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! request()->get('ldate') ?? '' !!}">
                            <span class="input-group-append ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Show Results">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-none" id="datatable-details">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.th_title') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.thumbnail') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.th_date') }}</th>
                        <th class="text-center">{{ trans('admin.th_vendor') }}</th>
                        <th class="text-center">{{ trans('admin.followers') }}</th>
                        <th class="text-center">{{ trans('admin.category') }}</th>
                        <th class="text-center">{{ trans('admin.course_title') }}</th>
                        <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $item)
                        <tr>
                            <td class="cu-p" data-toggle="modal" data-target="#description{{ $item->id }}">{{ $item->title }}</td>
                            <td class="text-center"><a href="{{ $item->image }}">{{ trans('admin.view') }}</a></td>
                            <td class="text-center" width="150">{{ date('d F Y : H:i',$item->created_at) }}</td>
                            <td class="text-center" title="{{ $item->user->username }}">{{ $item->user->name }}</td>
                            <td class="text-center">{{ $item->fans_count }}</td>
                            <td class="text-center">{{ !empty($item->category) ? $item->category->title : '' }}</td>
                            <td class="text-center">
                                @if (!empty($item->content))
                                    <a href="/product/{{ $item->content->id }}" target="_blank">{{ $item->content->title }}</a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->mode == 'publish')
                                    <span class="c-g f-w-b">{{ trans('admin.published') }}</span>
                                @else
                                    <span class="c-o">{{ trans('admin.waiting') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->mode == 'publish')
                                    <a href="/admin/request/record/draft/{{ $item->id }}" title="Add to waiting list"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                @else
                                    <a href="/admin/request/record/publish/{{ $item->id }}" title="Publish item"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                @endif
                                <a href="#" data-href="/admin/request/record/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('modals')
    @foreach($lists as $item)
        <div class="modal fade" role="dialog" id="description{{ $item->id }}">
            <div class="modal-dialog z-i-9999">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $item->description }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('admin.close') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endforeach
@endsection
