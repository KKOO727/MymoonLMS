@extends('admin.newlayout.layout',['breadcom'=>['Notifications','New Notification']])
@section('title')
    {{ trans('admin.new_notification') }}
@endsection
@section('page')

    @if(!empty(session('status')))
        @if(session('status') == 'error')
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>{{ trans('admin.notification_send_failed') }}</strong>
            </div>
        @else
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>
                    {{ trans('admin.notification_sent_successfully') }}
                </strong>
            </div>
        @endif
    @endif

    <section class="card">
        <div class="card-body">
            <form action="/admin/notification/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-11">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.receipts') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_type" class="form-control select2 recipent_selection">
                            <option value=""></option>
                            <option value="userone" @if(!empty(request()->get('recipent_type')) and request()->get('recipent_type') == 'userone') selected @endif>{{ trans('admin.single_user') }}</option>
                            <option value="users" @if(!empty(request()->get('recipent_type')) and request()->get('recipent_type') == 'users') selected @endif>{{ trans('admin.users_list') }}</option>
                            <option value="category" @if(!empty(request()->get('recipent_type')) and request()->get('recipent_type') == 'category') selected @endif>{{ trans('admin.user_groups') }}</option>
                            <option value="seller">{{ trans('admin.vendors') }}</option>
                            <option value="buyer">{{ trans('admin.cusomers') }}</option>
                            <option value="female">{{ trans('admin.females') }}</option>
                            <option value="male">{{ trans('admin.males') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group recipent" id="users">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.users') }}</label>
                    <div class="col-md-11" dir="ltr">
                        <select name="recipent_list_users[]" multiple class="form-control selectric text-left">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group @if(empty(request()->get('recipent_type')) or request()->get('recipent_type') != 'userone') recipent @endif" id="userone">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.user') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_list_user" class="form-control selectric">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if(!empty(request()->get('uid')) and request()->get('uid') == $user->id) selected @endif>{{ $user->username }} ({{ $user->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group recipent" id="category">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.user_group') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_list_category" class="form-control selectric">
                            @foreach($userCategory as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="msg" required></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.send') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.recipent_selection').change(function () {
                $('.recipent').hide();
                $('#' + $(this).val()).slideDown(300);
            })
        })
    </script>
@endsection


