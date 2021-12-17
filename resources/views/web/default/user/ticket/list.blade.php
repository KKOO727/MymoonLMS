@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="container">
        <div class="h-20"></div>
        <div class="row">
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">New Ticket</div>
                    <div class="body">
                        <form method="post" action="/user/ticket/store">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{ trans('main.title') }}</label>
                                <input type="text" name="title" class="form-control" id="inputDefault"
                                       @if(!empty($type) && $type == 'upgrade') value="{!! trans('Please upgrade my teacher account') !!}"
                                       readonly @endif required>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">Subject</label>
                                <select name="category_id" class="form-control font-s" required>
                                    <option value="">Please select</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" @if($type=='upgrade' && $cat->id == '1'){{'selected'}}@endif>{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label control-label-p">{{ trans('main.attachment') }}</label>
                                <div class="input-group" style="display: flex">
                                    <button id="lfm_attach" data-input="attach" data-preview="holder"
                                            class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="attach" class="form-control" dir="ltr" type="text" name="attach"
                                           value="{{ !empty($meta['attach']) ? $meta['attach'] : '' }}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal"
                                         data-target="#ImageModal" data-whatever="attach">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ trans('main.description') }}</label>
                                <textarea class="form-control" rows="7" name="msg" required></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left"
                                        value="Reply">{{ trans('main.send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 tab-con">
                @if(count($lists) == 0)
                    <div class="text-center">
                        <img src="/assets/default/images/empty/tickets.png">
                        <div class="h-20"></div>
                        <span class="empty-first-line">{{ trans('main.no_sup_ticket') }}</span>
                        <div class="h-20"></div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table ucp-table" id="ticket-table">
                            <thead class="back-blue">
                            <tr>
                                <th class="cell-ta">{{ trans('main.title') }}</th>
                                <th width="100" class="text-center">{{ trans('main.status') }}</th>
                                <th width="100" class="text-center">{{ trans('main.controls') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $item)
                                <tr>
                                    <td class="cell-ta">{{ $item->title }}</td>
                                    <td class="text-center">
                                        @if($item->mode == 'open')
                                            @if($item->messages->last()['mode'] == 'admin')
                                                Staff Reply
                                            @else
                                                Waiting
                                            @endif
                                        @else
                                            Closed
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/user/ticket/reply/{{ $item->id }}" title="View/Reply"><span
                                                class="crticon mdi mdi-message-text"></span></a>
                                        @if($item->mode == 'open')
                                            <a href="/user/ticket/close/{{ $item->id }}" title="Close Ticket"><span
                                                    class="crticon mdi mdi-close-octagon"></span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_attach').filemanager('file', {prefix: '/user/laravel-filemanager'});
    </script>
@endsection
