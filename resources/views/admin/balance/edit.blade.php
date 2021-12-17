@extends('admin.newlayout.layout',['breadcom'=>['Accounting','Edit Financial Document']])
@section('title')
    {{ trans('admin.edit_financial_doc') }}
@endsection
@section('page')

    <section class="card">
        <header class="card-heading">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
            </div>
            <h2 class="card-title">{{ trans('admin.edit_financial_doc') }}</h2>
        </header>
        <div class="card-body">

            <form action="/admin/balance/edit/store/{{ $item->id }}" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-8">
                        <input type="text" name="title" value="{{ $item->title }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('admin.amount') }}</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="price" value="{{ $item->price }}" class="form-control text-center numtostr">
                            <span class="input-group-addon click-for-upload cursor-pointer">{!! num2str($item->price) !!} {{ trans('admin.cur_dollar') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.document_type') }}</label>
                    <div class="col-md-8">
                        <select name="type" class="form-control">
                            <option value="add" class="f-w-b color-green" @if($item->type=="add") selected @endif>{{ trans('admin.addiction') }}</option>
                            <option value="minus" class="f-w-b color-red-i" @if($item->type=="minus") selected @endif>{{ trans('admin.addiction') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.username') }}</label>
                    <div class="col-md-8">
                        <select name="user_id" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('main.no_user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($item->user_id==$user->id) selected @endif>{{ !empty($user->name) ? $user->name : $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('main.time') }}</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="fdate" class="text-center form-control" value="{!! date('Y/m/d',$item->created_at) !!}" name="fdate" placeholder="Date" required>
                            <input type="hidden" id="date" name="date" value="{!! date('d-M-Y',$item->created_at) !!}">
                            <span class="input-group-addon fdatebtn" id="fdatebtn"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="time" class="form-control text-center" value="{!! to_latin_num(date('H:i',$item->created_at)) !!}" name="time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('main.description') }}</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="description" rows="6">{{ $item->description }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-11">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('main.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
