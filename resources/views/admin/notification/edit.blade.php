@extends('admin.newlayout.layout',['breadcom'=>['Notifications','Edit']])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.notifications') }}
@endsection
@section('page')

    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            <h2 class="panel-title">{{ trans('admin.th_edit') }} {{ $item->title }}</h2>
        </header>
        <div class="panel-body">

            <form action="/admin/notification/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-11">
                        <input type="text" value="{{ $item->title }}" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.receipts') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_type" class="form-control populate recipent_selection">
                            <option value=""></option>
                            <option value="userone" @if($item->recipent_type == 'userone') selected @endif >{{ trans('admin.single_user') }}</option>
                            <option value="users" @if($item->recipent_type == 'users') selected @endif>{{ trans('admin.users_list') }}</option>
                            <option value="category" @if($item->recipent_type == 'category') selected @endif>{{ trans('admin.user_groups') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group recipent" id="users" @if($item->recipent_type == 'users') {!! 'style="display: block;"' !!} @endif>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.users') }}</label>
                    <div class="col-md-11" dir="ltr">
                        <select name="recipent_list_users[]" multiple data-plugin-selectTwo class="form-control populate text-left">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($item->recipent_type == 'users' and in_array($user->id,unserialize($item->recipent_list))) {{ 'selected' }} @endif>{{ $user->username }} ({{ $user->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group recipent" id="user" @if($item->recipent_type == 'user') {!! 'style="display: block;"' !!} @endif>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.user') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_list_user" class="form-control populate">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($item->recipent_type == 'user' and $item->recipent_list == $user->id) {{ 'selected' }} @endif >{{ $user->username }} ({{ $user->name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group recipent" id="category" @if($item->recipent_type == 'category') {!! 'style="display: block;"' !!} @endif>
                    <label class="col-md-1 control-label" for="inputDefault">{{ trans('admin.user_group') }}</label>
                    <div class="col-md-11">
                        <select name="recipent_list_category" class="form-control populate">
                            @foreach($userCategory as $cat)
                                <option value="{{ $cat->id }}" @if($item->recipent_type == 'category' and $item->recipent_list == $cat->id) {{ 'selected' }} @endif >{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="msg" required>{{ $item->msg }}</textarea>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{ trans('admin.th_edit') }}</button>
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


