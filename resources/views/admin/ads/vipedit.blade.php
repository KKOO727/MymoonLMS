@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Featured Products','Edit']])
@section('title')
    {{ trans('admin.featured_products') }}
@endsection
@section('page')

    <section class="card">
        <header class="card-heading">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
            </div>
            <h2 class="card-title">{{ trans('admin.edit_featured') }}</h2>
        </header>
        <div class="card-body">
            <form action="/admin/ads/vip/edit/store/{{ $vip->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.course') }}</label>
                    <div class="col-md-4">
                        <select name="content_id" data-plugin-selectTwo class="form-control populate" id="type">
                            @foreach($contents as $content)
                                <option value="{{ $content->id }}" @if($vip->content_id == $content->id) selected @endif>{{ $content->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_status') }}</label>
                    <div class="col-md-4">
                        <select name="mode" class="form-control" required>
                            <option value="publish" @if($vip->mode == 'publish') selected @endif>{{ trans('admin.published') }}</option>
                            <option value="draft" @if($vip->mode == 'draft') selected @endif>{{ trans('admin.waiting') }}</option>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.start_end') }}</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="date" class="form-control" id="fdate" value="{{ date('Y-m-d',$vip->first_date) }}" name="fdate" required>
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="date" class="form-control" id="ldate" value="{{ date('Y-m-d',$vip->last_date) }}" name="ldate" required>
                            <span class="input-group-append ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span></span>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.type') }}</label>
                    <div class="col-md-4">
                        <select name="type" class="form-control">
                            <option value="slide" @if($vip->type == 'slide') selected @endif>{{ trans('admin.homepage_slider') }}</option>
                            <option value="category" @if($vip->type == 'category') selected @endif>{{ trans('admin.top_category') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.short_description') }}</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="8" name="description">{{ $vip->description }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault"></label>
                    <div class="col-md-8">
                        <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
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
                    <th class="text-center" width="120">{{ trans('admin.start_date') }}</th>
                    <th class="text-center" width="120">{{ trans('admin.end_date') }}</th>
                    <th class="text-center">{{ trans('admin.course') }}</th>
                    <th class="text-center" width="100">{{ trans('admin.type') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td class="text-center" width="80">{{ date('d F Y',$item->first_date) }}</td>
                        <td class="text-center" width="80">{{ date('d F Y',$item->last_date) }}</td>
                        <td class="text-center" width="50">
                            @if (!empty($item->content))
                                <a target="_blank" href="/product/{{ $item->content->id }}">{{ $item->content->title }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->type == 'slide' || $item->type == null)
                                {{ trans('admin.homepage_slider') }}
                            @else
                                {{ trans('admin.top_category') }}
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            @if($item->mode == 'publish')
                                <b class="color-green">{{ trans('admin.active') }}</b>
                            @elseif($item->mode == 'draft')
                                <b class="color-red-i">{{ trans('admin.disabled') }}</b>
                            @endif
                        </td>
                        <td class="text-center" width="50">
                            <a href="/admin/ads/vip/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/ads/vip/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection



