@extends('admin.newlayout.layout',['breadcom'=>['Courses','Categories',$item->title]])
@section('title')
    {{ trans('admin.subcategories') }}
@endsection
@section('page')

    <div class="card">
        <div class="card-body">
            <div class="tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#list" data-toggle="tab"> {{ trans('admin.childs') }} </a>
                    </li>
                    <li>
                        <a href="#newitem" data-toggle="tab">{{ trans('admin.new_child') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="list" class="tab-pane active">
                        <table class="table table-bordered table-striped mb-none" id="datatable-details">
                            <thead>
                            <tr>
                                <th class="text-center" width="36">{{ trans('admin.th_icon') }}</th>
                                <th>{{ trans('admin.child_title') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.link_title') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.th_commission') }}</th>
                                <th class="text-center" width="150">{{ trans('admin.badges_tab_courses_count') }}</th>
                                <th class="text-center" width="150">{{ trans('admin.cat_filters') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr>
                                    <td class="text-center"><img src="{{ $list->image }}" class="w-24 h-a"/></td>
                                    <td>{{ $list->title }}</td>
                                    <td class="text-center">{{ $list->class }}</td>
                                    <td class="text-center">{{ $list->commision }}</td>
                                    <td class="text-center">{{ !empty($list->contents_count) ? $list->contents_count : '0' }}</td>
                                    <td class="text-center">{{ !empty($list->filters_count) ? $list->filters_count : '0' }}</td>
                                    <td class="text-center">
                                        <a href="/admin/content/category/edit/{{ $list->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="/admin/content/category/filter/{{ $list->id }}" title="Filters"><i class="fa fa-tags" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/content/category/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="newitem" class="tab-pane ">
                        <form method="post" action="/admin/content/category/store" class="form-horizontal form-bordered">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.parrent_category') }}</label>
                                <div class="col-md-6">
                                    <select name="parent_id" class="form-control">
                                        <option value="{{ $item->id  }}">{{ $item->title }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.child_title') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="title" value="" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.link_title') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="class" value="" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.menu_icon') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="image" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="image" class="form-control" value="{{ $item->image ?? '' }}" type="text" name="image" dir="ltr" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.cat_page_icon') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="icon" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="icon" class="form-control" value="{{ $item->icon ?? '' }}" type="text" name="icon" dir="ltr" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="icon">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.cat_page_bg') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="background" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="background" class="form-control" value="{{ $item->background ?? '' }}" type="text" name="background" dir="ltr" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="background">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.cat_app_icon') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="app_icon" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="app_icon" class="form-control" value="{{ $item->app_icon ?? '' }}" type="text" name="app_icon" dir="ltr" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="app_icon">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.color_code') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="color" value="{{ $item->color }}" class="form-control text-center">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{ trans('admin.request_icon') }}</label>
                                <div class="col-md-6">
                                    <div class="input-group" style="display: flex">
                                        <button type="button" data-input="req_icon" data-preview="holder" class="lfm_image btn btn-primary">
                                            Choose
                                        </button>
                                        <input id="req_icon" class="form-control" value="{{ $item->req_icon ?? '' }}" type="text" name="req_icon" dir="ltr" >
                                        <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="req_icon">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.th_commission') }}</label>
                                <div class="col-md-6">
                                    <input type="number" name="commision" min="0" max="100" value="{{$item->commision }}" placeholder="%" class="form-control text-center">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



