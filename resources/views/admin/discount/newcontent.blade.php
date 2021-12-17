@extends('admin.newlayout.layout',['breadcom'=>['Promotions','New']])
@section('title')
    {{ trans('admin.new_promotion') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form action="/admin/discount/content/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.type') }}</label>
                    <div class="col-md-8">
                        <select name="type" class="form-control populate" id="type" required>
                            <option value=""></option>
                            <option value="content">{{ trans('admin.single_course') }}</option>
                            <option value="category">{{ trans('admin.category_discount') }}</option>
                            <option value="all">{{ trans('admin.all_courses_discount') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group hide-me content">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.course') }}</label>
                    <div class="col-md-8">
                        <select name="off_id_content" class="form-control populate" id="type" required>
                            @foreach($contents as $content)
                                <option value="{{ $content->id }}">{{ $content->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group hide-me category">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.category') }}</label>
                    <div class="col-md-8">
                        <select name="off_id_category" class="form-control populate" id="type">
                            @foreach($categoreis as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-md-5 control-label" for="inputDefault">{{ trans('admin.start_date') }}</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fdate" name="fdate" required>
                                    <span class="input-group-append fdatebtn" id="fdatebtn">
                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-5 control-label" for="inputDefault">{{ trans('admin.end_date') }}</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="date" class="form-control" id="ldate" name="ldate" required>
                                    <span class="input-group-append ldatebtn" id="ldatebtn">
                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-md-12 control-label" for="inputDefault">{{ trans('admin.amount') }}</label>
                            <div class="col-md-12">
                                <input type="number" min="0" max="99" name="off" class="form-control text-center" placeholder="Percent only (Eg: 20 for 20%)" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-12 control-label" for="inputDefault">{{ trans('admin.th_status') }}</label>
                            <div class="col-md-12">
                                <select name="mode" class="form-control populate" id="type">
                                    <option value="publish">{{ trans('admin.active') }}</option>
                                    <option value="draft">{{ trans('admin.disabled') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault"></label>
                    <div class="col-md-8">
                        <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('select[name="type"]').change(function () {
                $('.hide-me').hide();
                $('.' + $(this).val()).show();
            })
        })
    </script>
@endsection


