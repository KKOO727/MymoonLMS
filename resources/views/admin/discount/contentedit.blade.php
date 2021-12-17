@extends('admin.newlayout.layout',['breadcom'=>['Promotions','Edit']])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.promotions') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form action="/admin/discount/content/edit/store/{{ $discount->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.type') }}</label>
                    <div class="col-md-8">
                        <select name="type" class="form-control populate" id="type">
                            <option value=""></option>
                            <option value="content" @if($discount->type=='content') selected @endif>{{ trans('admin.single_course') }}</option>
                            <option value="category" @if($discount->type=='category') selected @endif>{{ trans('admin.category_discount') }}</option>
                            <option value="all" @if($discount->type=='all') selected @endif>{{ trans('admin.all_courses_discount') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group @if($discount->type != 'content') hide-me @endif content">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.courses') }}</label>
                    <div class="col-md-8">
                        <select name="off_id_content" class="form-control populate" id="type">
                            @foreach($contents as $content)
                                <option value="{{ $content->id }}" @if($discount->off_id == $content->id) selected @endif >{{ $content->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group @if($discount->type != 'category') hide-me @endif category">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.category') }}</label>
                    <div class="col-md-8">
                        <select name="off_id_category" class="form-control populate" id="type">
                            @foreach($categoreis as $category)
                                <option value="{{ $category->id }}" @if($discount->off_id == $category->id) selected @endif>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-md-4 control-label" for="inputDefault">{{ trans('admin.start_date') }}</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="date" id="fsdate" value="{{ date('Y-m-d',$discount->first_date) ?? '' }}" class="text-center form-control" name="fdate" placeholder="Start Date">
                                    <span class="input-group-append fdatebtn" id="fdatebtn">
                                    <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-4 control-label" for="inputDefault">{{ trans('admin.end_date') }}</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" value="{{ date('Y-m-d',$discount->last_date) }}" name="ldate" placeholder="End Date">
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
                            <label class="col-md-5 control-label" for="inputDefault">{{ trans('admin.amount') }}</label>
                            <div class="col-md-12">
                                <input type="number" min="0" max="99" name="off" value="{{ $discount->off }}" class="form-control text-center" placeholder="Percent only (Eg: 20 for 20%)" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-5 control-label" for="inputDefault">{{ trans('admin.th_status') }}</label>
                            <div class="col-md-12">
                                <select name="mode" class="form-control populate" id="type">
                                    <option value="publish" @if($discount->mode=='publish') selected @endif>{{ trans('admin.active') }}</option>
                                    <option value="draft" @if($discount->mode=='draft') selected @endif>{{ trans('admin.disabled') }}</option>
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


