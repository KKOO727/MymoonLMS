@extends('admin.newlayout.layout',['breadcom'=>[trans('admin.send_email')]])
@section('title')
    {!! trans('admin.send_email') !!}
@endsection
@section('page')

    @if(!empty(session('status')))
        @if(session('status') == 'error')
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>{{ trans('admin.email_unable') }}</strong>
            </div>
        @else
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>
                    {{ trans('admin.email_sent_successfully') }}
                </strong>
            </div>
        @endif
    @endif

    <section class="card">
        <div class="card-body">
            <form action="/admin/email/sendMail" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-11">
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.receipts') }}</label>
                    <div class="col-md-11" dir="ltr">
                        <select name="recipent[]" multiple class="form-control selectric text-left">
                            @foreach($users as $user)
                                <option value="{{ $user->email }}">{{ $user->username }} ({{ $user->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.templates') }}</label>
                    <div class="col-md-11">
                        <select id="template" name="template" class="form-control">
                            <option value=""></option>
                            @foreach($template as $temp)
                                <option value="{{ $temp->id }}">{{ $temp->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">{{ trans('admin.attachments') }}</label>
                    <div class="col-md-11">
                        <div class="input-group" style="display: flex">
                            <button id="lfm_attach" data-input="attach" data-preview="holder" class="btn btn-primary">
                                Choose
                            </button>
                            <input id="attach" class="form-control" type="text" dir="ltr" name="attach" value="{{ !empty($meta['attach']) ? $meta['attach'] : '' }}">
                            <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="attach">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="message" required></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.send') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection


@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_attach').filemanager('image');
    </script>
@endsection



