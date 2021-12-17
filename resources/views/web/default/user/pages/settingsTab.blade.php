<h3 class="paz no-margin violet"><i class="fas fa-cogs"></i> {{ trans('Settings') }}</h3>
<hr>
<form method="post" class="form-horizontal" action="/user/settings/statusUpdate">
    {{ csrf_field() }}
<table class="table table-borderless profileTable">
    <tr>
        <th>
        {{ trans('Account Status') }}</td>
        <td>

            <select name="mode" class="form-control font-s">

                    <option value="active" @if($user->mode == 'active'){{'selected'}}@endif>Active</option>
                    <option value="disabled" @if($user->mode == 'disabled'){{'selected'}}@endif>Delete Account</option>
                    <option value="deactive" @if($user->mode == 'deactive'){{'selected'}}@endif>Deactivated</option>

            </select>
        </td>
    </tr>
    <!--<tr>
        <th>{{ trans('main.language') }}</th>
        <td>
            <select name="meta_language" class="form-control">
                @foreach(languages() as $code => $title)
                    @if(in_array($code,json_decode(get_option('site_language_select'),true)))
                        <option value="{!! $code !!}"
                                @if(isset($meta['meta_language']) && $meta['meta_language'] == $code) selected @endif>{!! $title !!}</option>
                    @endif
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <th>{{ trans('Currency') }}</th>
        <td>
            <?php
            $currencies=array('USD','EUR');
            ?>
            <select name="meta_currency" class="form-control">
                @foreach($currencies as $cur)
                        <option value="{!! $cur !!}" @if(isset($meta['meta_currency']) && $meta['meta_currency'] == $cur) selected @endif>{!! $cur !!}</option>
                @endforeach
            </select>
        </td>
    </tr>-->
    <tr>
        <th></th>
        <td class="text-center"><input type="submit" value="Save" class="btn btn-lg btn-primary"></td>
    </tr>

</table>
</form>
