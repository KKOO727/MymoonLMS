@extends('admin.newlayout.layout',['breadcom'=>['Users','Groups','List']])
@section('title')
    {{ trans('admin.usergp_pagetitle') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-header">{{ trans('admin.usergp_pagetitle') }}</div>
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#list" data-toggle="tab"> {{ trans('admin.user_groups_tab_title') }} </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#newitem" data-toggle="tab">{{ trans('admin.new_user_group_tab_title') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="list" class="tab-pane active">
                    <table class="table table-bordered table-striped mb-none" id="datatable-details">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.user_groups_th_group_title') }}</th>
                            <th class="text-center" width="80">{{ trans('admin.th_discount') }}</th>
                            <th class="text-center" width="80">{{ trans('admin.th_commission') }}</th>
                            <th class="text-center" width="80">{{ trans('admin.th_users') }}</th>
                            <th class="text-center" width="80">{{ trans('admin.th_status') }}</th>
                            <th class="text-center" width="80">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list)
                            <tr>
                                <td>{{ $list->title }}</td>
                                <td class="text-center" width="80">%{{ $list->off }}</td>
                                <td class="text-center" width="80">%{{ $list->commision }}</td>
                                <td class="text-center" width="80"><a href="/admin/user/incategory/{{$list->id}}">{{ $list->users_count }}</a></td>
                                <td class="text-center">
                                    @if($list->mode == 'publish')
                                        <b class="c-g">{{ trans('admin.new_user_group_status_enabled') }}</b>
                                    @else
                                        <b class="c-r">{{ trans('admin.new_user_group_status_disables') }}</b>
                                    @endif
                                </td>
                                <td class="text-center" width="80">
                                    <a href="/admin/user/category/edit/{{ $list->id }}#newitem" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/category/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="newitem" class="tab-pane ">
                    <form method="post" action="/admin/user/category/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        @if($errors->any())
                            <ul>
                                {!!  implode('', $errors->all('<li style="color: red">:message</li>')) !!}
                            </ul>
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_user_group_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="title" value="" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_user_group_discount') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="off" value="" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_user_group_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_user_group_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" data-input="image" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image" class="form-control" type="text" name="image" dir="ltr" >
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.new_user_group_status') }}</label>
                            <div class="col-md-6">
                                <select name="mode" class="form-control populate" id="type">
                                    <option value="publish">{{ trans('admin.new_user_group_status_enabled') }}</option>
                                    <option value="draft">{{ trans('admin.new_user_group_status_disables') }}</option>
                                </select>
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
@endsection


