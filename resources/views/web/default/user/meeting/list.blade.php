@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.videolayout' : getTemplate() . '.user.layout_user.quizzes')

@if($user['vendor'] == 1)
    @section('tab7','active')
@else
    @section('tab1','active')
@endif

@section('tab')
    <div class="accordion-off">
        <ul id="accordion" class="accordion off-filters-li">
            <li class="open">
                <div class="link">
                    <h2>{!! trans('main.live_class') !!}</h2>
                    <i class="mdi mdi-chevron-down"></i>
                </div>
                <ul class="submenu" style="display: block;">
                    <div class="h-10"></div>
                    <form method="post" @if(!isset($edit)) action="/user/video/live/new/store" @else action="/user/video/live/edit/store/{!! $edit->id !!}" @endif>
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label>{!! trans('main.status') !!}</label>
                                    <select name="mode" class="form-control">
                                        <option value="active" @if(isset($edit) && $edit->mode == 'active') selected @endif>{!! trans('admin.active') !!}</option>
                                        <option value="done" @if(isset($edit) && $edit->mode == 'done') selected @endif>{!! trans('main.done') !!}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label>{!! trans('main.course') !!}</label>
                                    <select name="content_id" class="form-control">
                                        <option value="">{!! trans('admin.select_item') !!}</option>
                                        @foreach($courses as $course)
                                            <option value="{!! $course->id !!}" @if(isset($edit) && $edit->content_id == $course->id) selected @endif>{!! $course->title ?? '' !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label>{!! trans('main.date') !!}</label>
                                    <input type="date" class="form-control" name="date" value="{!! $edit->date ?? '' !!}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label>{!! trans('main.title') !!}</label>
                                    <input type="text" class="form-control" value="{!! $edit->title ?? '' !!}" name="title">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label>{!! trans('main.time') !!}</label>
                                        <input type="time" class="form-control" value="{!! $edit->time ?? '' !!}" name="time" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>{!!  trans('main.duration') !!}</label>
                                        <div class="input-group">
                                            <input type="text" value="{!! $edit->duration ?? '' !!}" class="form-control text-center" name="duration">
                                            <span class="input-group-addon">{!! trans('admin.minutes') !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </ul>
            </li>
            <li class="open">
                <div class="link"><h2>{{ trans('main.live_list') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu dblock">
                    <div class="h-10"></div>
                    <div class="table-responsive">
                        <table class="table ucp-table" id="request-table">
                            <thead class="thead-s">
                            <th class="cell-ta">{{ trans('main.title') }}</th>
                            <th class="text-center">{{ trans('main.course') }}</th>
                            <th class="text-center">{{ trans('main.date') }}</th>
                            <th class="text-center">{{ trans('main.time') }}</th>
                            <th class="text-center">{{ trans('main.duration') }}</th>
                            <th class="text-center">{{ trans('main.status') }}</th>
                            <th class="text-center" width="200">{{ trans('main.controls') }}</th>
                            </thead>
                            <tbody>
                                @foreach($list as $item)
                                    <tr>
                                    <td class="cell-ta">{!! $item->title ?? '' !!}</td>
                                    <td class="text-center"><a href="/product/{!! $item->content_id ?? '' !!}" target="_blank">{!! $item->content->title ?? '' !!}</a></td>
                                    <td class="text-center">{!! $item->date ?? '' !!}</td>
                                    <td class="text-center">{!! $item->time ?? '' !!}</td>
                                    <td class="text-center">{!! $item->duration ?? '' !!}</td>
                                    <td class="text-center">{!! $item->mode ?? '' !!}</td>
                                    <td class="text-center">
                                        <a href="#add_url_modal_{!! $item->id !!}" data-toggle="modal" title="Add Join Link" class="gray-s add_url_btn" data-id="{!! $item->id !!}"><span class="crticon mdi mdi-link"></span></a>
                                        <a href="/user/video/live/users/{{ $item->content_id }}" title="Content Users" class="gray-s"><span class="crticon mdi mdi-view-list"></span></a>
                                        <a href="/user/content/meeting/delete/{{ $item->id }}" title="Delete" class="gray-s"><span class="crticon mdi mdi-delete-forever"></span></a>
                                        <a href="/user/video/live/edit/{{ $item->id }}" title="Edit" class="gray-s"><span class="crticon mdi mdi-pencil"></span></a>
                                    </td>
                                    <div class="modal fade" id="add_url_modal_{!! $item->id !!}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">{!! trans('main.add_url') !!}</h4>
                                                </div>
                                                <form method="post" action="/user/video/live/url/store/{!! $item->id !!}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>{!! trans('main.system') !!}</label>
                                                            <select name="type" id="system-type" class="form-control" required>
                                                                <option value="">Select one</option>
                                                                <option value="zoom" @if($item->type == 'zoom') selected @endif>Zoom(auto Generated)</option>
                                                                <option value="jitsti" @if($item->type == 'jitsti') selected @endif>Jitsti</option>
                                                                <option value="google_meet" @if($item->type == 'google_meet') selected @endif>Google Meet</option>
                                                                <option value="big_blue_button" @if($item->type == 'big_blue_button') selected @endif>Big Blue Button</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{!! trans('main.join_link') !!}</label>
                                                            <input type="text" class="form-control" value="{!! $item->join_url ?? '' !!}" name="join_url" required>
                                                        </div>
                                                        <div class="form-group start-link" @if($item->start_url == null && $item->start_url == '') style="display: none" @endif>
                                                            <label>{!! trans('main.start_link') !!}</label>
                                                            <input type="text" class="form-control" value="{!! $item->start_url ?? '' !!}" name="start_url">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{!! trans('main.meeting_password') !!}</label>
                                                            <input type="text" class="form-control" value="{!! $item->password ?? '' !!}" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-generate-zoom-meeting pull-right" data-content="{!! $item->content_id !!}" style="display: none">auto generate zoom meeting</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">{!! trans('main.save') !!}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="h-10"></div>
                </ul>
            </li>
        </ul>
    </div>

@stop
@section('script')
    <script>
        $('#system-type').on('change',function () {
            if($(this).val() == 'zoom'){
                $('.btn-generate-zoom-meeting').show();
            }else{
                $('.btn-generate-zoom-meeting').hide();
                $('.start-link').hide();
            }
        })
    </script>
    <script>
        $('document').ready(function () {
            $('.btn-generate-zoom-meeting').on('click', function () {
                let btn = $(this);
                $.getJSON('/user/content/meeting/action?action=zoom&content_id='+$(this).attr('data-content'),function (response) {
                    if(response.status == 0){
                        btn.parent().parent().find('.start-link').show();
                        btn.parent().parent().find('input[name="join_url"]').val(response.join_url);
                        btn.parent().parent().find('input[name="start_url"]').val(response.start_url);
                    }else{
                        $.notify({
                            message: 'problem with zoom jwt'
                        }, {
                            type: 'danger',
                            allow_dismiss: false,
                            z_index: '99999999',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            position: 'fixed'
                        });
                    }
                })
            })
        })
    </script>
@stop

