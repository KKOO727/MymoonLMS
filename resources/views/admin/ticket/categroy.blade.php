@extends('admin.newlayout.layout',['breadcom'=>['Support','Departments']])
@section('title')
    {{ trans('admin.support_departments') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#list" data-toggle="tab"> {{ trans('admin.departments') }} </a></li>
                    <li class="nav-item"><a class="nav-link" href="#newitem" data-toggle="tab">{{ trans('admin.new_department') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="list" class="tab-pane active">
                        <table class="table table-bordered table-striped mb-none" id="datatable-details">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.department') }}</th>
                                <th class="text-center" width="200">{{ trans('admin.support_tickets') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr>
                                    <td>{{ $list->title }}</td>
                                    <td class="text-center">{{ $list->tickets_count ?? '0' }}</td>
                                    <td class="text-center">
                                        <a href="/admin/ticket/category/edit/{{ $list->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/ticket/category/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="newitem" class="tab-pane ">
                        <form method="post" action="/admin/ticket/category/store" class="form-horizontal form-bordered">
                            {{ csrf_field() }}
                            @if($errors->any())
                                <ul>
                                    {!!  implode('', $errors->all('<li style="color: red">:message</li>')) !!}
                                </ul>
                            @endif
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                                <div class="col-md-6">
                                    <input type="text" name="title" value="" class="form-control">
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

