@extends('admin.newlayout.layout',['breadcom'=>['Settings','Social Networks']])
@section('title')
    {{ trans('admin.social_networks') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div id="day" class="tab-pane active">
                <form method="post" action="/admin/setting/social/store" class="form-horizontal form-bordered">
                    {{ csrf_field() }}

                    @if($errors->any())
                        <ul>
                            {!!  implode('', $errors->all('<li style="color: red">:message</li>')) !!}
                        </ul>
                    @endif

                    <input type="hidden" name="id" value="{{ $item->id ?? '' }}">

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                        <div class="col-md-8">
                            <input type="text" name="title" value="{{ $item->title ?? '' }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_icon') }}</label>
                        <div class="col-md-8">

                            <div class="input-group" style="display: flex">
                                <button type="button" data-input="icon" data-preview="holder" class="btn btn-primary lfm_image">
                                    Choose
                                </button>
                                <input id="icon" class="form-control" type="text" name="icon" dir="ltr" value="{{ $item->icon ?? '' }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="icon">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">{{ trans('admin.link_address') }}</label>
                        <div class="col-md-8">
                            <input type="text" name="link" value="{{ $item->link ?? '' }}" class="form-control text-left">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.sort') }}</label>
                        <div class="col-md-2">
                            <input type="number" name="sort" value="{{ $item->sort ?? '' }}" class="form-control text-center">
                        </div>
                        <div class="h-20"></div>
                        <div class="col-md-6">
                            <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                        </div>
                    </div>


                </form>
                <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                    <thead>
                    <tr>
                        <th class="text-center" width="60">{{ trans('admin.th_icon') }}</th>
                        <th>{{ trans('admin.th_title') }}</th>
                        <th class="text-center" width="100">{{ trans('admin.link_address') }}</th>
                        <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $list)
                        <tr>
                            <td class="text-center"><img src="{{ $list->icon  }}" width="24"/></td>
                            <td>{{ $list->title  }}</td>
                            <td class="text-center"><a href="{{ $list->link }}" target="_blank">{{ trans('admin.view') }}</a></td>
                            <td class="text-center">
                                <a href="/admin/setting/social/edit/{{ $list->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/setting/social/delete/{{ $list->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

