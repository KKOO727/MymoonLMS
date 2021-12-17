@extends(getTemplate().'.user.layout.articlelayout')

@section('tab1','active')
@section('tab')
    <div class="h-20"></div>
    <div class="h-10"></div>
    <form method="post" action="/user/article/store" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{ trans('main.title') }}</label>
            <div class="col-md-6 tab-con">
                <input type="text" class="form-control" name="title">
            </div>
            <label class="control-label col-md-1 tab-con">{{ trans('main.category') }}</label>
            <div class="col-md-4 tab-con">
                <select class="form-control font-s" name="cat_id">
                    @foreach(contentMenu() as $menu)
                        <optgroup label="{{ $menu['title'] }}">
                            @if(count($menu['submenu']) == 0)
                                <option value="{{ $menu['id'] }}">{{ $menu['title'] }}</option>
                            @else
                                @foreach($menu['submenu'] as $sub)
                                    <option value="{{ $sub['id'] }}">{{ $sub['title'] }}</option>
                                @endforeach
                            @endif
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{ trans('main.article_summary') }}</label>
            <div class="col-md-11 tab-con">
                <textarea class="ckeditor" name="pre_text"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{ trans('main.description') }}</label>
            <div class="col-md-11 tab-con">
                <textarea class="ckeditor" name="text"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{ trans('main.thumbnail') }}</label>
            <div class="col-md-5 tab-con">
                <div class="input-group" style="display: flex">
                    <button id="lfm_image" data-input="image" data-preview="holder" class="btn btn-primary">
                        Choose
                    </button>
                    <input id="image" class="form-control"  dir="ltr" type="text" name="image">
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                        <span class="input-group-text">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
            <label class="control-label col-md-1 tab-con">{{ trans('main.status') }}</label>
            <div class="col-md-3 tab-con">
                <select class="form-control font-s" name="mode">
                    <option value="draft">{{ trans('main.draft') }}</option>
                    <option value="request">{{ trans('main.send_for_review') }}</option>
                    <option value="delete">{{ trans('main.unpublish_request') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="Save Article" class="btn btn-custom pull-left btn-100-p">
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script type="text/javascript" src="/assets/default/ckeditor/ckeditor.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            CKEDITOR.config.language = 'en';
        });
        $('#lfm_image').filemanager('file', {prefix: '/user/laravel-filemanager'});
    </script>
@endsection


