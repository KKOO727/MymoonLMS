@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ get_option('site_title','') }} - {{ trans('main.requests') }}
    -
    {{ trans('main.new_request') }}
@endsection
@section('page')
    <div class="container-fluid">
        <div class="row cat-tag-section">
            <div class="container">
                <div class="col-md-5 col-xs-12">

                </div>
                <div class="col-md-5 col-xs-12 text-left"></div>
                <div class="col-md-2 col-xs-12 text-left req-s">
                    <a href="/request" class="btn btn-custom"><span>{{ trans('main.requests_list') }}</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="h-20"></div>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="col-md-7 col-xs-12 tab-con">
                <div class="ucp-section-box sbox3">
                    <div class="header back-orange"><span>{{ trans('main.request_course') }}</span></div>
                    <div class="body">
                        <form method="post" action="/request/store" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2 control-label tab-con">{{ trans('main.title') }}</label>
                                <div class="col-md-10 tab-con">
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label tab-con">{{ trans('main.category') }}</label>
                                <div class="col-md-10 tab-con">
                                    <select class="form-control fos-11" name="category_id">
                                    @foreach($setting['category'] as $mainCategory)
                                        @if(count($mainCategory->childs) > 0)
                                            <optgroup label="{{$mainCategory->title }}">
                                                    @foreach($mainCategory->childs as $child)
                                                        <option value="{{ $child->id }}">{{ $child->title }}</option>
                                                    @endforeach
                                            </optgroup>
                                        @else
                                            <optgroup label="{{$mainCategory->title}}">
                                                <option value="{{$mainCategory->id }}">{{$mainCategory->title }}</option>
                                            </optgroup>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label tab-con">{{ trans('main.description') }}</label>
                                <div class="col-md-10 tab-con">
                                    <textarea name="description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-12 tab-con">
                                    <button class="pull-left btn btn-custom" type="submit"><span>{{ trans('main.send_req') }}</span></button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12 tab-con">
                <div class="ucp-section-box sbox3">
                    <div class="header back-orange"><span>{{ trans('main.term_rules') }}</span></div>
                    <div class="body">
                        {!! get_option('request_term','') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
