@extends('admin.newlayout.layout',['breadcom'=>['Notifications','Templates']])
@section('title')
    {{ trans('admin.notification_template') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <p>{{ trans('admin.username') }} : [u.username]</p>
            <hr>
            <p>{{ trans('admin.real_name') }} : [u.name]</p>
            <hr>
            <p>{{ trans('admin.email') }} : [u.email]</p>
            <hr>
            <p>{{ trans('admin.new_user_group_title') }} : [u.c.title]</p>
            <hr>
            <p>{{ trans('admin.badge_title') }} : [u.m.title]</p>
            <hr>
            <p>{{ trans('admin.course_title') }} : [c.title]</p>
            <hr>
            <p>{{ trans('admin.item_doc') }} : [c.id]</p>
            <hr>
            <p>{{ trans('admin.course_req_title') }} : [r.title]</p>
            <hr>
            <p>{{ trans('admin.support_ticket_title') }} : [t.title]</p>
            <hr>
            <p>{{ trans('admin.financial_doc_amount') }} : [b.amount]</p>
            <hr>
            <p>{{ trans('admin.financial_doc_desc') }} : [b.description]</p>
            <hr>
            <p>{{ trans('admin.financial_doc_type') }} : [b.type]</p>
        </div>
    </section>

    <section class="card">
        <div class="card-body">

            <form action="/admin/notification/template/edit" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$item->id ?? '' }}">
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-11">
                        <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="template" required>
                                {{ $item->template ?? '' }}
                        </textarea>
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


