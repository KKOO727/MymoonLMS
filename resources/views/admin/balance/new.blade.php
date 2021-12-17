@extends('admin.newlayout.layout',['breadcom'=>['Accounting','New Financial Document']])

@section('title')
    {{ trans('admin.new_financial_doc') }}
@endsection

@section('page')
    <section class="card">
        <div class="card-body">
            <form action="/admin/balance/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-8">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('admin.amount') }}</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="number" name="price" required class="form-control text-center numtostr">
                            <span class="input-group-addon click-for-upload cursor-pointer"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.target_account') }}</label>
                    <div class="col-md-8">
                        <select name="account" class="form-control" required>
                            <option value="credit">{{ trans('admin.account_balance') }}</option>
                            <option value="income">{{ trans('admin.user_income') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.document_type') }}</label>
                    <div class="col-md-8">
                        <select name="type" class="form-control" required>
                            <option value="add" class="c-g f-w-b">{{ trans('admin.addiction') }}</option>
                            <option value="minus" class="c-r f-w-b">{{ trans('admin.deduction') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.username') }}</label>
                    <div class="col-md-8">
                        <select name="user_id" data-plugin-selectTwo class="form-control populate select2" required>
                            <option value="">{{ trans('admin.business_account') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if(!empty(request()->get('user')) and request()->get('user')==$user->id) selected @endif>{{ !empty($user->name) ? $user->name : $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.description') }}</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="description" rows="6"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-11">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection
