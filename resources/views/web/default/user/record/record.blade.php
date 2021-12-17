@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.requestVideoLayout' : getTemplate() . '.user.layout_user.requestVideoLayout')

@section('video_tab2','active')

@section('video_tab')
    <div class="accordion-off col-xs-12">
        <ul id="accordion" class="accordion off-filters-li">
            <li @if(isset($record->id)) class="open" @endif>
                <div class="link"><h2>{{ trans('main.submit_future_course') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu" @if(isset($record->id)) style="display: block;" @endif>
                    <div class="h-10"></div>
                    <form method="post" @if(isset($record->id)) action="/user/video/record/edit/store/{{ $record->id }}" @else action="/user/video/record/store" @endif class="form form-horizontal">
                        {{ csrf_field() }}
                        <div class="h-10"></div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{ trans('main.title') }}</label>
                            <div class="col-md-5 tab-con">
                                <input type="text" name="title" value="{{ !empty($record) ? $record->title : '' }}" class="form-control">
                            </div>
                            <label class="control-label col-md-1 tab-con">{{ trans('main.category') }}</label>
                            <div class="col-md-5 tab-con">
                                <select name="category_id" class="form-control font-s">
                                    <option value="0">{{ trans('main.select_category') }}</option>
                                    @foreach($menus as $menu)
                                        @if($menu->parent_id == 0)
                                            <optgroup label="{{ $menu->title }}">
                                                @if(count($menu->childs)>0)
                                                    @foreach($menu->childs as $sub)
                                                        <option value="{{ $sub->id }}" @if(isset($record->category_id) and $sub->id == $record->category_id) selected @endif>{{ $sub->title }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $menu->id }}" @if(isset($record->category_id) and $menu->id == $record->category_id) selected @endif>{{ $menu->title }}</option>
                                                @endif
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{ trans('main.course') }}</label>
                            <div class="col-md-5 tab-con">
                                <select name="off_id" class="form-control font-s">
                                    <option value="0" @if(isset($record->content_id) and $record->content_id == 0) selected @endif>{{ trans('main.recording') }}</option>
                                    @foreach($userContent as $uc)
                                        <option value="{{ $uc->id }}" @if(isset($record->content_id) and $record->content_id == $uc->id) selected @endif>{{ $uc->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-md-1 tab-con">{{ trans('main.thumbnail') }}</label>
                            <div class="col-md-5 tab-con">
                                <div class="input-group">
                                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="image"><span class="formicon mdi mdi-eye"></span></span>
                                    <input type="text" name="image" dir="ltr" value="{{ !empty($record->image) ? $record->image : '' }}" class="form-control">
                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{ trans('main.description') }}</label>
                            <div class="col-md-5 tab-con">
                                <textarea class="form-control" name="description">{{ !empty($record->description) ? $record->description : '' }}</textarea>
                            </div>
                            <label class="control-label col-md-1 tab-con"></label>
                            <div class="col-md-5 tab-con">
                                <button type="submit" class="btn btn-custom pull-left"><span>{{ trans('main.save_changes') }}</span></button>
                            </div>
                        </div>
                    </form>
                    <div class="h-10"></div>
                </ul>
            </li>
            <li class="open">
                <div class="link"><h2>{{ trans('main.future_courses_list') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu dblock">
                    <div class="h-10"></div>
                    @if(count($lists) == 0)
                        <div class="text-center">
                            <img src="/assets/default/images/empty/Videos.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{ trans('main.no_future_course') }}</span>
                            <div class="h-10"></div>
                            <span class="empty-second-line">
                                <span>{{ trans('main.future_course_desc') }}</span>
                            </span>
                            <div class="h-20"></div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ucp-table" id="record-table">
                                <thead class="thead-s">
                                <th class="cell-ta">{{ trans('main.title') }}</th>
                                <th class="text-center" width="100">{{ trans('main.thumbnail') }}</th>
                                <th class="text-center" width="100">{{ trans('main.followers') }}</th>
                                <th class="text-center">{{ trans('main.course') }}</th>
                                <th class="text-center">{{ trans('main.category') }}</th>
                                <th class="text-center">{{ trans('main.date') }}</th>
                                <th class="text-center" width="50">{{ trans('main.status') }}</th>
                                <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                                </thead>
                                <tbody>
                                @foreach($lists as $item)
                                    <tr class="text-center">
                                        <td class="cell-ta">{{ $item->title }}</td>
                                        <td class="text-center">
                                            <a href="{{ $item->image }}" target="_blank">{{ trans('main.view') }}</a>
                                        </td>
                                        <td class="text-center">{{ $item->fans_count }}</td>
                                        <td class="text-center">
                                            @if (!empty($item->content))
                                                {{ $item->content->title ?? 'Future Course (Recording)' }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($item->category))
                                                {{ $item->category->title }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d F Y H:i',$item->created_at)  }}</td>
                                        <td>
                                            @if($item->mode == "publish")
                                                <b class="green-s">{{ trans('main.published') }}</b>
                                            @elseif($item->mode == "delete")
                                                <b class="red-s">{{ trans('main.delete') }}</b>
                                            @else
                                                <b class="orange-s">{{ trans('main.disabled') }}</b>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="gray-s" href="/user/video/record/edit/{{ $item->id }}" title="Edit"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                            <a class="gray-s" href="/user/video/record/delete/{{ $item->id }}" onclick="return confirm('Are you sure to delete item?');" title="Delete"><span class="crticon mdi mdi-delete-forever"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
@endsection

@section('script')
    <script>$('#buy-hover').addClass('item-box-active');</script>
    <script>
        $(function () {
            var objCal = new AMIB.persianCalendar('first_date_t',
                {
                    btnClassName: 'first_date_btn',
                    extraInputID: "first_date",
                    extraInputFormat: "DD-MM-YYYY"
                });
            $('.first_date_btn').click(function (e) {
                objCal.showHidePicker();
            });
        });
        $(function () {
            var objCal1 = new AMIB.persianCalendar('last_date_t',
                {
                    btnClassName: 'last_date_btn',
                    extraInputID: "last_date",
                    extraInputFormat: "DD-MM-YYYY"
                });
            $('.last_date_btn').click(function (e) {
                objCal1.showHidePicker();
            });
        });
    </script>
@endsection
