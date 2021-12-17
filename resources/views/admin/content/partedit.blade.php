@extends('admin.newlayout.layout',['breadcom'=>['Part','Edit',$item->title]])
@section('title')
    {{ trans('admin.th_edit') }}{{ trans('admin.parts') }}
@endsection
@section('page')

    <div class="tabs">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="#main" class="nav-link active" data-toggle="tab"> {{ trans('admin.general') }} </a>
            </li>
            <li class="nav-item">
                <a href="#parts" class="nav-link" data-toggle="tab">{{ trans('admin.parts') }}</a>
            </li>
            <li class="nav-item">
                <a href="#part" class="nav-link" data-toggle="tab">{{ $part->title }}</a>
            </li>

        </ul>
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div id="main" class="tab-pane active">
                        <form action="/admin/content/store/{{$item->id}}/main" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                                <div class="col-md-10">
                                    <input type="text" value="{{ $item->title }}" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{!! trans('admin.categories') !!}</label>
                                <div class="col-md-10">
                                    <select name="category_id" class="form-control">
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id ?? '' !!}" @if($item->category_id == $category->id) selected @endif>{!! $category->title ?? '' !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.course_type') }}</label>
                                <div class="col-md-10">
                                    <select name="type" class="form-control" disabled>
                                        <option value="single" @if($item->type == '1') selected @endif>{{ trans('One To One') }}</option>
                                        <option value="course" @if($item->type == '2') selected @endif>{{ trans('Group Course') }}</option>
                                        <option value="webinar" @if($item->type == '3') selected @endif>{{ trans('Video Course') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.price') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="price" value="{{  !empty($item->price) ? $item->price : ''}}" class="form-control text-center" id="product_price" @if($item->price == 0) disabled="disabled" @endif>
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text">@if(!empty($item->price)) {{ num2str($item->price) }} @endif {{ trans('admin.cur_dollar') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_status') }}</label>
                                <div class="col-md-10">
                                    <select name="mode" class="form-control" required>
                                        <option value="publish" @if($item->mode=='publish') selected @endif>{{ trans('admin.published') }}</option>
                                        <option value="request" @if($item->mode=='request') selected @endif>{{ trans('admin.review_request') }}</option>
                                        <option value="waiting" @if($item->mode=='delete') selected @endif>{{ trans('admin.unpublish_request') }}</option>
                                        <option value="draft" @if($item->mode=='draft') selected @endif>{{ trans('admin.pending') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                    <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="parts" class="tab-pane ">
                        <table class="table table-bordered table-striped mb-none" id="datatable-details">
                            <thead>
                            <tr>
                                <th>{{ trans('admin.th_title') }}</th>
                                <th class="text-center" width="150">{{ trans('admin.th_date') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.convert_status') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.volume') }}(MB)</th>
                                <th class="text-center" width="100">{{ trans('admin.duration') }}({{ trans('admin.minute') }})</th>
                                <th class="text-center" width="100">{{ trans('admin.th_status') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item->parts as $parts)
                                <tr>
                                    <td>{{ $parts->title  }}&nbsp;@if($parts->free == 1 || $item->price == 0)({{ trans('admin.free') }})@endif</td>
                                    <td class="text-center" width="150">{{ date('d F Y : H:i',strtotime($parts->created_at)) }}</td>
                                    <td class="text-center" width="100">
                                        @php
                                            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                                            $file = $storagePath.'source/content-'.$parts->content->id.'/video/part-'.$parts->id.'.mp4';
                                        @endphp
                                        @if(file_exists($file))
                                            <i class="fa fa-check c-g"></i>
                                        @else
                                            <i class="fa fa-times c-r"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $parts->size ?? '0' }}</td>
                                    <td class="text-center">{{ $parts->duration ?? '0' }}</td>
                                    <td class="text-center" width="100">
                                        @if($parts->mode == 'publish')
                                            <b class="c-b">{{ trans('admin.published') }}</b>
                                        @elseif($parts->mode == 'draft')
                                            <b class="c-o">{{ trans('admin.draft') }}</b>
                                        @elseif($parts->mode == 'request')
                                            <span class="c-g">{{ trans('admin.review_request') }}</span>
                                        @elseif($parts->mode == 'delete')
                                            <span class="c-r">{{ trans('admin.unpublish_request') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/content/edit/{{ $item->id }}/part/{{ $parts->id }}#part" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/content/part/delete/{{ $parts->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="part" class="tab-pane ">
                        <form action="/admin/content/partstore/{{ $part->id }}" class="form-horizontal form-bordered" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                                <div class="col-md-10">
                                    <input type="text" value="{{ $part->title  }}" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea class="summernote" name="description">{{ $part->description  }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.volume') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="number" min="0" name="size" value="{{$part->size }}" class="form-control text-center">
                                        <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text">MB</span>
                                </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.duration') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="number" min="0" name="duration" value="{{$part->duration }}" class="form-control text-center">
                                        <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text">{{ trans('admin.minute') }}</span>
                                </span>
                                    </div>
                                </div>
                            </div>

                            <!--
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.course_cover') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_screen">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                        <input type="text" name="upload_screen" dir="ltr" value="{{ $part->upload_screen }}" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                                    </div>
                                </div>
                            </div>
                            -->
                            <!--
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.course_thumbnail') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_image">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                        <input type="text" name="upload_image" dir="ltr" value="{{ $part->upload_image }}" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                                    </div>
                                </div>
                            </div>
                            -->

                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.video') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#VideoModal" data-whatever="upload_video">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                        <input type="text" name="upload_video" dir="ltr" value="{{ $part->upload_video }}" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">{{ trans('admin.th_status') }}</label>
                                <div class="col-md-8">
                                    <select name="mode" class="form-control">
                                        <option value="publish" @if($part->mode == 'publish') selected @endif>{{ trans('admin.published') }}</option>
                                        <option value="draft" @if($part->mode == 'draft') selected @endif>{{ trans('admin.draft') }}</option>
                                        <option value="request" @if($part->mode == 'request') selected @endif>{{ trans('admin.review_request') }}</option>
                                        <option value="delete" @if($part->mode == 'delete') selected @endif>{{ trans('admin.unpublish_request') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-md-2 control-label">{{ trans('admin.sort') }}</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control text-center" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="h-40"></div>
                                        <label class="custom-switch">
                                            <input type="hidden" name="free" value="0">
                                            <input type="checkbox" name="free" value="1" class="custom-switch-input" @if($part->free == 1) checked="checked" }} @endif />
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description" for="inputDefault">{{ trans('admin.free_course') }}</label>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                    <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>



                    <div id="progressbar" class="row progressprogress-striped progress-sm m-md hidden">
                        <div class="progress-bar w-0" role="progre ssbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">0%</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
