@extends('admin.newlayout.layout',['breadcom'=>['Users','Rating','Admin Panel']])
@section('title')
    {{ trans('admin.badges_pagetitle') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#day" data-toggle="tab"> {{ trans('admin.badges_tab_com_age') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#videocount" data-toggle="tab"> {{ trans('admin.badges_tab_courses_count') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#sellcount" data-toggle="tab"> {{ trans('admin.badges_tab_sales') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#buycount" data-toggle="tab"> {{ trans('admin.badges_tab_purchase') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#supportrate" data-toggle="tab"> {{ trans('admin.badges_tab_support_feedback') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#productrate" data-toggle="tab"> {{ trans('admin.badges_tab_course_rating') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#postrate" data-toggle="tab">{{ trans('admin.badges_tab_postal_feedback') }} </a></li>
            </ul>
            <hr>
            <div class="tab-content">
                <div id="day" class="tab-pane active">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="day">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image1" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image1" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.registration_days') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">{{ trans('admin.from') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ trans('admin.days') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="100">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }} </th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list)
                            @if($list->mode == 'day')
                                <tr>
                                    <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                    <td>{{ $list->description }}</td>
                                    <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                    <td class="text-center">{{ $list->gift }}</td>
                                    <td class="text-center">{{ $list->commision }}</td>
                                    <td class="text-center">
                                        <a href="/admin/user/rate/edit/{{ $list->id }}/day#day" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/day" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="videocount" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="videocount">
                        <input type="hidden" name="edit" value="{{ $item->id ?? ''  }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">

                                <div class="input-group" style="display: flex">
                                    <button data-input="image2" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image2" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <span class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.badges_tab_courses_count') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-prepend">
                                                            <span class="input-group-text">{{ trans('admin.from') }}</span>
														</span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.courses') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list) @if($list->mode == 'videocount')
                            <tr>
                                <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                <td>{{ $list->description }}</td>
                                <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                <td class="text-center">{{ $list->gift }}</td>
                                <td class="text-center">{{ $list->commision }}</td>
                                <td class="text-center">
                                    <a href="/admin/user/rate/edit/{{ $list->id }}/videocount#videocount" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/videocount" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endif @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="sellcount" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="sellcount">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image3" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image3" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.sales_count') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-prepend">
                                                            <span class="input-group-text">{{ trans('admin.from') }}</span>
														</span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.sales') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list) @if($list->mode == 'sellcount')
                            <tr>
                                <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                <td>{{ $list->description }}</td>
                                <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                <td class="text-center">{{ $list->gift }}</td>
                                <td class="text-center">{{ $list->commision }}</td>
                                <td class="text-center">
                                    <a href="/admin/user/rate/edit/{{ $list->id }}/sellcount#sellcount" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/sellcount" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endif @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="buycount" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="buycount">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image4" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image4" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.purchases_count') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-prepend">
                                                            <span class="input-group-text">{{ trans('admin.from') }}</span>
														</span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.purchases') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list) @if($list->mode == 'buycount')
                            <tr>
                                <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                <td>{{ $list->description }}</td>
                                <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                <td class="text-center">{{ $list->gift }}</td>
                                <td class="text-center">{{ $list->commision }}</td>
                                <td class="text-center">
                                    <a href="/admin/user/rate/edit/{{ $list->id }}/buycount#buycount" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/buycount" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endif @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="supportrate" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="supportrate">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image5" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image5" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.badges_tab_support_feedback') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-prepend">
                                                            <span class="input-group-text">{{ trans('admin.from') }}</span>
														</span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.stars') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list) @if($list->mode == 'supportrate')
                            <tr>
                                <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                <td>{{ $list->description }}</td>
                                <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                <td class="text-center">{{ $list->gift }}</td>
                                <td class="text-center">{{ $list->commision }}</td>
                                <td class="text-center">
                                    <a href="/admin/user/rate/edit/{{ $list->id }}/supportrate#supportrate" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/supportrate" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endif @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="productrate" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="productrate">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image6" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image6" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.badges_tab_course_rating') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-prepend">
                                                            <span class="input-group-text">{{ trans('admin.from') }}</span>
														</span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.stars') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list) @if($list->mode == 'productrate')
                            <tr>
                                <td class="text-center"><img src="{{ $list->image }}" width="24"/></td>
                                <td>{{ $list->description }}</td>
                                <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                <td class="text-center">{{ $list->gift }}</td>
                                <td class="text-center">{{ $list->commision }}</td>
                                <td class="text-center">
                                    <a href="/admin/user/rate/edit/{{ $list->id }}/productrate#productrate" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/productrate" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endif @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="postrate" class="tab-pane">
                    <form method="post" action="/admin/user/rate/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <input type="hidden" name="mode" value="postrate">
                        <input type="hidden" name="edit" value="{{$item->id ?? '' }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" name="description" value="{{$item->description ?? '' }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button data-input="image7" data-preview="holder" class="lfm_image btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image7" class="form-control" type="text" name="image" value="{{ $item->image ?? '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.gift_charge') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="price" value="{{$item->gift ?? '' }}" class="form-control text-center numtostr">
                                    <div class="input-group-append click-for-upload cu-p">
                                        <span class="input-group-text">@if(!empty($item->gift)) {{ num2str($item->gift) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.badge_commission') }}</label>
                            <div class="col-md-6">
                                <input type="number" name="commision" value="{{$item->commision ?? ''}}" placeholder="%" class="form-control text-center">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.badges_tab_postal_feedback') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">{{ trans('admin.from') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->start ?? '' }}" name="start" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.to') }}</span>
                                    </span>
                                    <input type="number" class="form-control" value="{{$item->end ?? '' }}" name="end" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">{{ trans('admin.stars') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                    <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                        <thead>
                        <tr>
                            <th class="text-center" width="60">{{ trans('admin.badge_icon') }}</th>
                            <th>{{ trans('admin.badge_title') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.role') }}</th>
                            <th class="text-center" width="200">({{ trans('admin.cur_dollar') }}) {{ trans('admin.gift_charge') }}</th>
                            <th class="text-center" width="200">{{ trans('admin.badge_commission') }}</th>
                            <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $list)
                            @if($list->mode == 'postrate')
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ $list->image }}" width="24"/>
                                    </td>
                                    <td>{{ $list->description }}</td>
                                    <td class="text-center">{{ str_replace(',',' to ',$list->value) }}</td>
                                    <td class="text-center">{{ $list->gift }}</td>
                                    <td class="text-center">{{ $list->commision }}</td>
                                    <td class="text-center">
                                        <a href="/admin/user/rate/edit/{{ $list->id }}/postrate#postrate" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/user/rate/delete/{{ $list->id }}/postrate" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


