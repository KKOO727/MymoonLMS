@extends('admin.newlayout.layout',['breadcom'=>['Settings','Course Settings']])
@section('title')
    {{ trans('admin.course_settings') }}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div id="main" class="tab-pane active">
                <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="main_page_vip_container" value="0">
                                    <input type="checkbox" name="main_page_vip_container" value="1" class="custom-switch-input" @if(isset($_setting['main_page_vip_container']) and $_setting['main_page_vip_container'] == 1)) checked @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable_featured') }}</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="main_page_newest_container" value="0">
                                    <input type="checkbox" name="main_page_newest_container" value="1" class="custom-switch-input" @if(isset($_setting['main_page_newest_container']) and $_setting['main_page_newest_container'] == 1)) checked @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable_latest') }}</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="main_page_popular_container" value="0">
                                    <input type="checkbox" name="main_page_popular_container" value="1" class="custom-switch-input" @if(isset($_setting['main_page_popular_container']) and $_setting['main_page_popular_container'] == 1)) checked @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable_popular') }}</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="category_most_sell_container" value="0">
                                    <input type="checkbox" name="category_most_sell_container" value="1" class="custom-switch-input" @if(isset($_setting['category_most_sell_container']) and $_setting['category_most_sell_container'] == 1)) checked @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable_top_sales') }}</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="custom-switches-stacked">
                                <label class="custom-switch">
                                    <input type="hidden" name="main_live_class" value="0">
                                    <input type="checkbox" name="main_live_class" value="1" class="custom-switch-input" @if(isset($_setting['main_live_class']) and $_setting['main_live_class'] == 1)) checked @endif />
                                    <span class="custom-switch-indicator"></span>
                                    <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable_main_live_class') }}</label>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-5 control-label" for="inputDefault">{{ trans('admin.slider_time') }}</label>
                        <div class="col-md-4">
                            <input type="number" class="form-control text-center" name="main_page_slider_timer" value="{{ $_setting['main_page_slider_timer'] ?? '' }}" placeholder="ms"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">{{ trans('admin.items_in_category_page') }}</label>
                        <div class="col-md-4">
                            <input type="number" class="text-center form-control" name="category_content_count" value="{{ $_setting['category_content_count'] ?? 0 }}" maxlength="3">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label"></label>
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
