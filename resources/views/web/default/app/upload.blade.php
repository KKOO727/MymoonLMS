<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap/css/bootstrap-3.2.rtl.css">
    <link rel="stylesheet" href="/assets/default/app/vendor/jquery-toast-plugin/dist/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/assets/default/app/vendor/swicher/css/switcher.css">
    <link rel="stylesheet" href="/assets/default/app/vendor/chosen/chosen.min.css">
    <link rel="stylesheet" href="/assets/default/app/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/default/app/css/style.css">
    <script src="/assets/default/vendor/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/default/app/vendor/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="/assets/default/app/vendor/jquery-toast-plugin/dist/jquery.toast.min.js" type="text/javascript"></script>
    <script src="/assets/default/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js" type="text/javascript"></script>
    <script src="/assets/default/app/vendor/swicher/js/jquery.switcher.min.js" type="text/javascript"></script>
    <script src="/assets/default/app/js/script.js" type="text/javascript"></script>
    <title>{{ trans('main.upload') }}</title>
</head>
<body dir="rtl">
<input type="hidden" id="user_id" value="{{ $user->id }}">
<input type="hidden" id="content_id" value="{{ $edit->id }}">
<div class="steps step-1 steps-active">
    <div class="sbox2 step-header">{{ trans('main.basic_information') }}</div>
    <div class="step-body">
        <form method="post" action="/api/upload/store/{{ $user->id }}" id="step_1_form">
            {{ csrf_field() }}
            <div class="form-group">
                <label>{{ trans('main.course_type') }}</label>
                <select class="form-control" name="type">
                    <option value="single" @if(isset($edit) && $edit->type == 'single') selected @endif>{{ trans('main.single') }}</option>
                    <option value="course" @if(isset($edit) && $edit->type == 'course') selected @endif>{{ trans('main.course') }}</option>
                </select>
            </div>
            <div class="from-group">
                <label>{{ trans('main.publish_type') }}</label>
                <select class="form-control" name="private">
                    <option value="1" @if(isset($edit) && $edit->private == '1') selected @endif>{{ trans('main.exclusive') }}</option>
                    <option value="0" @if(isset($edit) && $edit->private == '0') selected @endif>{{ trans('main.open') }}</option>
                </select>
            </div>
            <div class="h-10"></div>
            <div class="form-group">
                <label>{{ trans('main.title') }}</label>
                <input type="text" name="title" id="step-1-title" placeholder="30 - 60 Characters" class="form-control" value="{{ $edit->title }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.description') }}</label>
                <textarea type="text" name="content" id="step-1-description" rows="10" class="form-control">{!! $edit->content !!}</textarea>
            </div>
            <div class="form-group text-left">
                <input type="button" class="btn btn-app" id="btn-step-1-next" value="Next Step">
            </div>
        </form>
    </div>
</div>
<div class="steps step-2 ">
    <div class="sbox2 step-header">{{ trans('main.extra_info') }}</div>
    <form method="post" id="step_2_form">
        {{ csrf_field() }}
        <div class="step-body">
            <div class="form-group">
                <label>{{ trans('main.tags') }}</label>
                <textarea class="form-control" name="tag" id="step-2-tag" data-role="tagsinput" rows="10" placeholder="Press enter between tags">{!! $edit->tag !!}</textarea>
            </div>
            <div class="form-group">
                <label>{{ trans('main.category') }}</label>
                <select name="category_id" id="step-2-category_id" class="form-control">
                    <option value="">{{ trans('main.select_category') }}</option>
                </select>
            </div>
            <div class="h-10"></div>
            <div class="form-group">
                <span class="pull-right">{{ trans('main.free_course') }}</span>
                <input type="hidden" name="price" value="1">
                <input type="checkbox" name="price" value="0" class="pull-left" id="step-2-price-enable">
            </div>
            <div class="h-20"></div>
            <div class="form-group">
                <span class="pull-right">{{ trans('main.postal_delivery') }}</span>
                <input type="hidden" name="post" value="0">
                <input type="checkbox" name="post" value="1" class="pull-left" id="step-2-post-enable">
            </div>
            <div class="h-20"></div>
            <div class="form-group">
                <span class="pull-right">{{ trans('main.supported_course') }}</span>
                <input type="hidden" name="support" value="0">
                <input type="checkbox" name="support" value="1" class="pull-left" id="step-2-support-enable">
            </div>
            <div class="h-20"></div>
            <div class="form-group">
                <label>{{ trans('main.price') }}</label>
                <div class="input-group">
                    <input type="number" min="500" class="form-control text-center" name="price" id="step-2-price">
                    <span class="input-group-addon">{{ trans('main.cur_dollar') }}</span>
                </div>
            </div>
            <div class="h-10"></div>
            <div class="form-group">
                <label>{{ trans('main.postal_price') }}</label>
                <div class="input-group">
                    <input type="number" min="500" class="form-control text-center" name="post_price" id="step-2-post_price" disabled>
                    <span class="input-group-addon">{{ trans('main.cur_dollar') }}</span>
                </div>
            </div>
            <div class="h-10"></div>
            <div class="form-group">
                <label>{{ trans('main.prerequisites') }}</label>
                <select name="precourse[]" class="form-control chosen-select" multiple>
                    @foreach($contents as $content)
                        <option value="{{ $content->id }}">{{ $content->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="h-10"></div>
            <div class="form-group text-left">
                <input type="button" class="btn btn-app pull-left" id="btn-step-2-next" value="Next">
                <input type="button" class="btn btn-app pull-right" id="btn-step-2-prev" value="Previous">
            </div>
            <div class="h-20"></div>
        </div>
    </form>
</div>
<div class="steps step-3">
    <div class="sbox2 step-header">{{ trans('main.images') }}</div>
    <div class="step-body">
        <div class="form-group">
            <label>{{ trans('main.course_cover') }}</label>
            <input type="file" name="cover" class="form-control" placeholder="Select a file">
        </div>
        <div class="h-10"></div>
        <div class="form-group">
            <label>{{ trans('main.course_thumbnail') }}</label>
            <input type="file" name="thumbnail" class="form-control" placeholder="Select a file">
        </div>
        <div class="h-10"></div>
        <div class="form-group">
            <label>{{ trans('main.demo') }}</label>
            <input type="file" name="video" class="form-control" placeholder="Select a file">
        </div>
        <div class="h-10"></div>
        <div class="form-group">
            <label>{{ trans('main.documents') }}&nbsp;</label>
            <input type="file" name="document" class="form-control" placeholder="Select a file">
        </div>
        <div class="h-20"></div>
        <div class="form-group text-left">
            <input type="button" class="btn btn-app pull-left" id="btn-step-3-next" value="Publish">
            <input type="button" class="btn btn-app pull-right" id="btn-step-3-prev" value="Previous">
        </div>
    </div>
</div>
<div class="loader-container">
    <div class="loader"></div>
</div>
<script>
    $(function () {
        $('.chosen-select').select2();
    })
</script>
</body>
</html>
