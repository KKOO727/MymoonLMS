@extends(getTemplate().'.user.layout.partlayout')

@section('tab1','active')

@section('tab')
    <div class="row">
        <div class="h-20"></div>
        <div class="col-md-7 col-xs-12">
            <div class="ucp-section-box">
                <div class="header back-orange">{{ trans('main.edit_part') }}</div>
                <div class="body">
                    <form action="/user/content/part/edit/store/{{ $part->id }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="content_id" value="{{ $part->content->id }}">
                        <div class="form-group">
                            <label class="control-label" for="inputDefault">{{ trans('main.title') }}</label>
                            <input type="text" name="title" value="{{ $part->title }}" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="inputDefault">{{ trans('main.description') }}</label>
                            <textarea class="form-control" rows="7" name="description">{{ $part->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans('main.volume') }}</label>
                            <div class="input-group">
                                <input type="number" min="0" name="size" value="{{ $part->size }}" class="form-control text-center">
                                <span class="input-group-addon click-for-upload img-icon-s">{{ trans('main.mb') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans('main.duration') }}</label>
                            <div class="input-group">
                                <input type="number" min="0" name="duration" value="{{ $part->duration }}" class="form-control text-center">
                                <span class="input-group-addon click-for-upload img-icon-s">{{ trans('main.minute') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans('main.video_file') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#VideoModal" data-whatever="upload_video"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                <input type="text" name="upload_video" dir="ltr" value="{{ $part->upload_video }}" class="form-control">
                                <span class="input-group-addon click-for-upload img-icon-s"><i class="fa fa-upload" aria-hidden="true"></i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{ trans('main.sort') }}</label>
                            <div data-plugin-spinner data-plugin-options='{ "value":0, "min": 0, "max": 100 }'>
                                <input type="number" value="{{ $part->number }}" class="spinner-input form-control" maxlength="3">
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-orange pull-left" type="submit">{{ trans('main.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="ucp-section-box">
                <div class="header back-green">{{ trans('main.term_rules') }}</div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>
@endsection
