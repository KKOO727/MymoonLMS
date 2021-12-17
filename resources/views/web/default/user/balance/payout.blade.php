<h3 class="paz no-margin violet"><i class="fas fa-files-o"></i> {{ trans('Payout') }}</h3>
<hr>
<?php $currency=getCurrency();?>
<div class="h-20"></div>
<div class="">
    <form class="form-horizontal" action="/user/payout" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="cur" value="{{$currency}}">
        <table class="table table-borderless profileTable">
            <tbody><tr>
                <th> Amount  ({{$currency}}) {{currencySign($currency)}}</th>
                <td class="text-left">
                    <input type="text" placeholder="" name="amount" class="form-control" required>
                </td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td class="text-left">
                    <input type="radio" name="processor" value="paypal" class="form-radio" required> Paypal<br>
                    <input type="radio" name="processor" value="bank" class="form-radio" required> Bank
                </td>
            </tr>
            <tr>
                <th>Paypal/Bank Details</th>
                <td class="text-left">
                    <textarea name="details" class="form-control"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <input type="submit" class="btn btn-primary" value="Submit Request">
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <span class="violet">** 1.5% withdrawal charge will be deducted from your withdrawal amount</span>
    <div class="h-20"></div>

    <div class="h-20"></div>

</div>
<div class="h-20"></div>
<div class="h-20"></div>
@section('script')
@endsection
