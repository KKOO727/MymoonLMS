@extends(getTemplate() . '.user.layout.layout')

@section('pages')

    @if(!empty(session('Message')))
        {!! session('Message') !!}
    @endif

    <div class="h-20"></div>
    <form method="post" action="/user/channel/store">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="col-md-1 control-label" for="inputDefault">{{ trans('main.title') }}</label>
            <div class="col-md-11">
                <input type="text" name="title" class="form-control" id="inputDefault" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label" for="inputDefault">{{ trans('main.link') }}</label>
            <div class="col-md-11">
                <input type="text" name="username" class="form-control" id="inputDefault" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{ trans('main.cover') }}</label>
            <div class="col-md-11">
                <div class="input-group" style="display: flex">
                    <button type="button" data-input="image" data-preview="holder" class="lfm_load btn btn-primary">
                        Choose
                    </button>
                    <input id="image" class="form-control" type="text" name="image" dir="ltr" >
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                    <span class="input-group-text">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{ trans('main.icon') }}</label>
            <div class="col-md-11">
                <div class="input-group" style="display: flex">
                    <button type="button" data-input="avatar" data-preview="holder" class="lfm_load btn btn-primary">
                        Choose
                    </button>
                    <input id="avatar" class="form-control" type="text" name="avatar" dir="ltr" >
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar">
                    <span class="input-group-text">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{ trans('main.description') }}</label>
            <div class="col-md-11">
                <textarea class="form-control" name="description"></textarea>
            </div>
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-custom pull-left" value="Save Changes">{{ trans('main.save_changes') }}</button>
        </div>
    </form>
@endsection

@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('.lfm_load').filemanager('file',{prefix: '/user/laravel-filemanager'});
        $('#channel-hover').addClass('item-box-active');
    </script>
@endsection
