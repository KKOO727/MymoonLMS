<h3 class="paz no-margin violet"><i class="fas fa-lock"></i> {{ trans('Security') }}</h3>
<hr>
<form method="post" class="form-horizontal" action="/user/security/change">
    {{ csrf_field() }}
    <table class="table table-borderless profileTable">
        <tr>
            <th>{{ trans('main.new_password') }}</th>
            <td><input type="password" name="password" class="form-control text-center"></td>
        </tr>
        <tr>
            <th>{{ trans('main.retype_password') }}</th>
            <td><input type="password" name="repassword" class="form-control text-center"></td>
        </tr>

        <tr>
            <th></th>
            <td class="text-center"><input type="submit" value="Save" class="btn btn-lg btn-primary"></td>
        </tr>
    </table>
</form>
