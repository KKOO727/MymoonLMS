@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="container">
    <div class="h-20"></div>
    <div class="row">
        <div class="col-md-6 col-xs-12 tab-con">
            <div class="ucp-section-box">
                <div class="header back-red">{{ trans('main.reply') }}</div>
                <div class="body">
                    <form method="post" action="/user/ticket/reply/store">
                        {{ csrf_field() }}
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <div class="form-group">
                            <textarea class="form-control" placeholder="Reply..." rows="7" name="msg" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label control-label-p">{{ trans('main.attachment') }} </label>
                            <div class="input-group" style="display: flex">
                                <button type="button" id="lfm_attach" data-input="attach" data-preview="holder" class="btn btn-primary">
                                    Choose
                                </button>
                                <input id="attach" class="form-control"  dir="ltr" type="text" name="attach" value="{{ !empty($meta['attach']) ? $meta['attach'] : '' }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="attach">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-custom pull-left" value="Send">{{ trans('main.send') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 tab-con">
            @foreach($ticket->messages as $msg)
                @if($msg->mode == 'user')
                    <div class="plan group">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{ trans('main.user') }}-{{ $msg->user->name }}
                                    <span class="pull-left">[{{ date('d F y h:i',$msg->created_at) }}]&nbsp;</span>
                                </h4>
                                <div class="plan-content pos-rel">
                                    {!! $msg->msg !!}
                                    @if($msg->attach != null && $msg->attach != '')
                                        <br>
                                        <a href="{!! $msg->attach !!}" target="_blank" class=""><span class="crticon mdi mdi-paperclip"></span>&nbsp;Attachment</a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="plan one">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{{ trans('main.staff') }}
                                    <span class="pull-left">[{{ date('d F y h:i',$msg->created_at) }}]&nbsp;</span>
                                </h4>
                                <div class="plan-content pos-rel">
                                    {!! $msg->msg !!}
                                    @if($msg->attach != null && $msg->attach != '')
                                        <br>
                                        <a href="{!! $msg->attach !!}" target="_blank" class="pull-left attach-s"><span class="crticon mdi mdi-paperclip"></span>&nbsp;Attachment</a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            @endforeach
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
