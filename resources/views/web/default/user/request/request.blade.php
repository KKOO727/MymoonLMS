@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.requestVideoLayout' : getTemplate() . '.user.layout_user.requestlayout')

@section('video_tab1','active')
@section('video_tab')
    <div class="accordion-off col-xs-12">
        <ul id="accordion" class="accordion off-filters-li">
            <li @if(isset($request->id)) class="open" @endif>
                <div class="link"><h2>{{ trans('main.new_request') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu" @if(isset($request->id)) style="display: block;" @endif>
                    <div class="h-10"></div>
                    <form method="post" @if(isset($request->id)) action="/user/video/request/edit/store/{{ $request->id }}" @else action="/user/video/request/store" @endif class="form form-horizontal">
                        {{ csrf_field() }}
                        <div class="h-10"></div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{ trans('main.title') }}</label>
                            <div class="col-md-5 tab-con">
                                <input type="text" name="title" value="{{ $request->title ?? '' }}" class="form-control">
                            </div>
                            <label class="control-label col-md-1 tab-con">{{ trans('main.category') }}</label>
                            <div class="col-md-5 tab-con">
                                <select name="category_id" class="form-control font-s">
                                    @foreach(selectMenu() as $menu)
                                        <optgroup label="{{ $menu['title'] }}">
                                            @foreach($menu['submenu'] as $sub)
                                                <option value="{{ $sub['id'] }}" @if(isset($request->category_id) and $request->category_id == $sub['id']) selected @endif>{{ $sub['title'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{ trans('main.description') }}</label>
                            <div class="col-md-5 tab-con">
                                <textarea class="form-control" name="description">{{ $request->description ?? '' }}</textarea>
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
            @if($user['vendor'] == 1)
                <li class="open">
                    <div class="link"><h2>{{ trans('main.received_requests') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                    <ul class="submenu dblock">
                        <div class="h-10"></div>
                        <div class="table-responsive">
                            <table class="table ucp-table">
                                <thead class="thead-s">
                                <th class="cell-ta">{{ trans('main.title') }}</th>
                                <th class="text-center">{{ trans('main.description') }}</th>
                                <th class="text-center" width="100">{{ trans('main.followers') }}</th>
                                <th class="text-center">{{ trans('main.course') }}</th>
                                <th class="text-center">{{ trans('main.category') }}</th>
                                <th class="text-center">{{ trans('main.date') }}</th>
                                </thead>
                                <tbody>
                                @foreach($lists as $item)
                                    @if($item->user_id == $user['id'] and $item->mode == 'publish')
                                        <tr class="text-center">
                                            <td class="cell-ta">{{ $item->title }}</td>
                                            <td class="text-center"><span class="img-icon-s" data-toggle="modal" data-target="#description{{ $item->id }}">{{ trans('main.view') }}</span></td>

                                            <div id="description{{ $item->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">{{ trans('main.description') }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ !empty($item->description) ? $item->description : 'No description' }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <td class="text-center">{{ $item->fans_count }}</td>
                                            <td class="text-center">{!!  !empty($item->content->title) ? $item->content->title : '<b class="img-icon-s orange-s" data-toggle="modal" data-target="#suggestion'.$item->id.'">Not selected</b>' !!}</td>
                                            <td class="text-center">{{ $item->category->title }}</td>
                                            <td class="text-center">{{ date('d F Y H:i',$item->created_at)  }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </ul>
                </li>
            @endif
            <li class="open">
                <div class="link"><h2>{{ trans('main.my_requests') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu dblock">
                    <div class="h-10"></div>
                    @if(count($lists) == 0)
                        <div class="text-center">
                            <img src="/assets/default/images/empty/Request.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{ trans('main.no_requests') }}</span>
                            <div class="h-10"></div>
                            <span class="empty-second-line">
                                <a href="javascript:void(0);">{{ trans('main.submit_request') }}</a>
                            </span>
                            <div class="h-20"></div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ucp-table" id="request-table">
                                <thead class="thead-s">
                                <th class="cell-ta">{{ trans('main.title') }}</th>
                                <th class="text-center">{{ trans('main.description') }}</th>
                                <th class="text-center" width="100">{{ trans('main.followers') }}</th>
                                <th class="text-center">{{ trans('main.course') }}</th>
                                <th class="text-center">{{ trans('main.category') }}</th>
                                <th class="text-center">{{ trans('main.date') }}</th>
                                <th class="text-center">{{ trans('main.responds') }}</th>
                                <th class="text-center" width="50">{{ trans('main.status') }}</th>
                                <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                                </thead>
                                <tbody>
                                @foreach($lists as $item)
                                    @if($item->requester_id == $user['id'])
                                        <tr class="text-center">
                                            <td class="cell-ta">{{ $item->title }}</td>
                                            <td class="text-center"><span data-toggle="modal" class="img-icon-s" data-target="#description{{ $item->id }}">{{ trans('main.view') }}</span></td>

                                            <div id="description{{ $item->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">{{ trans('main.description') }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ !empty($item->description) ? $item->description : 'No description' }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <td class="text-center">{{ $item->fans_count }}</td>
                                            <td class="text-center">
                                                @if (!empty($item->content))
                                                    <a href="/product/{{ $item->content->id }}" class="gray-s">{{ !empty($item->content->title) ? $item->content->title : 'Not selected' }}</a>
                                                @else
                                                    Not selected
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->category->title ?? '' }}</td>
                                            <td class="text-center">{{ date('d F Y H:i',$item->created_at)  }}</td>
                                            <td class="text-center">
                                                @if($item->content_id == '')
                                                    <b class="img-icon-s green-s" data-toggle="modal" data-target="#suggestion{{ $item->id }}"> <span class="badge">{{ $item->suggestions_count }}</span> {{ trans('main.view') }} </b>
                                                @else
                                                    <b class="blue-s">{{ trans('main.selected') }}</b>
                                                @endif
                                            </td>

                                            <div id="suggestion{{ $item->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">{{ trans('main.responds') }}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            @foreach($item->suggestions as $suggest)
                                                                <p><input type="radio" class="contentSuggest cont-sug" name="suggest{{ $item->id }}" data-id="{{ $item->id }}" data="{{ $suggest->content->id }}"><a href="/product/{{ $suggest->content->id }}" target="_blank" class="suggest-modal-item">{{ $suggest->content->title }}</a><span> {{ trans('main.responded_by') }} </span><a href="/profile/{{ $suggest->user->id }}" class="suggest-modal-item" target="_blank">{{ !empty($suggest->user->name) ? $suggest->user->name : $suggest->user->username }}</a></p>
                                                            @endforeach
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="/user/video/request/admit" method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="request_id" value="{{ $item->id }}">
                                                                <input type="hidden" name="content_id" id="contentid{{ $item->id }}">
                                                                <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                                                <button type="submit" class="btn btn-custom pull-right" id="btnsubmit{{ $item->id }}" disabled="disabled">{{ trans('main.accept_this_respond') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <td>
                                                @if($item->mode == "publish" and isset($item->content->id))
                                                    <b class="blue-s">{{ trans('main.closed') }}</b>
                                                @elseif($item->mode == "publish")
                                                    <b class="green-s">{{ trans('main.published') }}</b>
                                                @elseif($item->mode == "delete")
                                                    <b class="red-s">{{ trans('main.delete') }}</b>
                                                @else
                                                    <b class="orange-s">{{ trans('main.waiting') }}</b>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->content_id == '')
                                                    <a class="gray-s" href="/user/video/request/edit/{{ $item->id }}" title="Edit"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                                    <a class="gray-s" href="/user/video/request/delete/{{ $item->id }}" onclick="return confirm('Are you sure to delete item?');" title="Delete"><span class="crticon mdi mdi-delete-forever"></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
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
    @if($user['vendor'] == 1)
        <script>$('#buy-hover').addClass('item-box-active');</script>
    @else
        <script>$('#request-hover').addClass('item-box-active');</script>
    @endif
    <script>
        $('.contentSuggest').click(function () {
            var dataId = $(this).attr('data');
            var id = $(this).attr('data-id');
            $('#contentid' + id).val(dataId);
            $('#btnsubmit' + id).removeAttr('disabled');
        })
    </script>
@endsection
