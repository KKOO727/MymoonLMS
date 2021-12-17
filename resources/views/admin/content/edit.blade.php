@extends('admin.newlayout.layout',['breadcom'=>['Courses','Edit',$item->title]])
@section('title')
    {{ trans('admin.edit_course') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#main" data-toggle="tab"> {{ trans('admin.general') }} </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#parts" data-toggle="tab">{{ trans('admin.parts') }}</a>
                    </li>
                </ul>
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
                                    <?php
                                    $cats=explode(',',$item->category_id);
                                    ?>
                                    @foreach($cats as $category)
{{getCategoryFullName($category)}},
                                    @endforeach
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
                                <th class="text-center" width="50">{{ trans('admin.convert_status') }}</th>
                                <th class="text-center" width="50">{{ trans('admin.volume') }} (MB)</th>
                                <th class="text-center" width="50">{{ trans('admin.duration') }} ({{ trans('admin.minute') }})</th>
                                <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                                <th class="text-center" width="100">{{ trans('admin.th_controls') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item->parts as $part)
                                <tr>
                                    <td>{{ $part->title  }}&nbsp;@if($part->free == 1 || $item->price == 0)(Free)@endif</td>
                                    <td class="text-center" width="150">{{ date('d F Y : H:i',strtotime($item->created_at)) }}</td>
                                    <td class="text-center" width="50">
                                        @php
                                            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                                            $file = $storagePath.'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';
                                        @endphp
                                        @if(file_exists($file))
                                            <i class="fa fa-check c-g"></i>
                                        @else
                                            <i class="fa fa-times c-r"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ !empty($part->size) ? $part->size : '0' }}</td>
                                    <td class="text-center">{{ !empty($part->duration) ? $part->duration : '0' }}</td>
                                    <td class="text-center" width="100">
                                        @if($part->mode == 'publish')
                                            <b class="c-b">{{ trans('admin.published') }}</b>
                                        @elseif($part->mode == 'draft')
                                            <b class="c-g">{{ trans('admin.draft') }}</b>
                                        @elseif($part->mode == 'request')
                                            <span class="c-g">{{ trans('admin.review_request') }}</span>
                                        @elseif($part->mode == 'delete')
                                            <span class="c-r">{{ trans('admin.unpublish_request') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/content/edit/{{ $item->id }}/part/{{ $part->id }}#part" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/content/part/delete/{{ $part->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        import Index from "../../../../public/assets/default/vendor/flot/examples/zooming/index.html";
        $('#free_course').change(function () {
            if ($(this).is(':checked')) {
                $('#product_price').attr('disabled', 'disabled');
            } else {
                $('#product_price').removeAttr('disabled');
            }
        });
        export default {
            components: {Index}
        }
    </script>
@endsection


