@extends('admin.newlayout.layout',['breadcom'=>['Settings','Social Networks']])
@section('title')
    {{ trans('admin.view_templates') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-header">
            <h3>{{ trans('admin.add_new_template') }}</h3>
        </div>
        <div class="card-body">
            <div class="tab-pane active">
                <form method="post" action="/admin/setting/view_templates/store" class="form-horizontal form-bordered">
                    {{ csrf_field() }}

                    @if($errors->any())
                        <ul>
                            {!!  implode('', $errors->all('<li style="color: red">:message</li>')) !!}
                        </ul>
                    @endif

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.folder') }}</label>
                        <div class="col-md-8">
                            <input type="text" name="folder" placeholder="{{ trans('admin.add_folder_name') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1" class="custom-switch-input"/>
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">status</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>{{ trans('admin.view_templates_list') }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                <thead>
                <tr>
                    <th>{{ trans('admin.folder') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.status') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $list)
                    <tr>
                        <td>{{ $list->folder  }}</td>
                        <td class="text-center">
                            @if ($list->status)
                                {{ trans('admin.active') }}
                            @else
                                {{ trans('admin.disabled') }}
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/admin/setting/view_templates/toggle/{{ $list->id }}" title="{{ ($list->status) ? 'Disable' : 'Active' }}">
                                <i class="fa fa-arrow-{{ ($list->status) ? 'down' : 'up' }}" style="font-size: 18px;margin-right: 16px" aria-hidden="true"></i>
                            </a>
                            <a href="#" data-href="/admin/setting/view_templates/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" style="font-size: 18px" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

