@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Edit Plans']])
@section('title')
    {{ trans('admin.edit_plan') }}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            <form action="/admin/ads/plan/edit/store/{{ $plan->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-10">
                        <input type="text" name="title" value="{{ $plan->title }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">{{ trans('admin.price') }}</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="price" value="{{ $plan->price }}" class="form-control text-center numtostr">
                            <span class="input-group-append click-for-upload cursor-pointer">
                                <span class="input-group-text">{{ trans('admin.cur_dollar') }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label">{{ trans('admin.duration') }}</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="number" value="{{ $plan->day }}" name="day" class="form-control text-center">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="description" required>{{ $plan->description }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="custom-switches-stacked">
                            <label class="custom-switch">
                                <input type="hidden" name="mode" value="draft">
                                <input type="checkbox" name="mode" value="publish" class="custom-switch-input" @if($plan->mode=='publish') checked @endif />
                                <span class="custom-switch-indicator"></span>
                                <label class="custom-switch-description" for="inputDefault">{{ trans('admin.publish_item') }}</label>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

