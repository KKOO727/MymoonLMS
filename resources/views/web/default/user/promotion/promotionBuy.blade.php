@extends(getTemplate() . '.user.layout.videolayout')

@section('tab5','active')
@section('tab')
    <div class="col-md-12 col-xs-12">
        <div class="h-20"></div>
        <form method="post" class="form-horizontal" action="/user/video/promotion/buy/pay">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-md-2 tab-con">{{ trans('main.choose_plan') }}</label>
                <div class="col-md-4 tab-con">
                    <select class="form-control font-s" name="plan_id">
                        @foreach($plans as $pl)
                            <option value="{{ $pl->id }}" @if($plan->id == $pl->id) selected @endif>{{ $pl->title }} ( {{ currencySign() }}{{ $pl->price }} )</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-2 tab-con">{{ trans('main.course') }}</label>
                <div class="col-md-4 tab-con">
                    <select class="form-control font-s" name="content_id">
                        @foreach($userContent as $uc)
                            <option value="{{ $uc->id }}">{{ $uc->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 tab-con">{{ trans('main.additional_description') }}</label>
                <div class="col-md-3 tab-con">
                    <input type="text" class="form-control" name="description" placeholder="Optional">
                </div>
                <label class="control-label col-md-2 tab-con">{!! trans('main.gateway') !!}</label>
                <div class="col-md-3 tab-con">
                    <select name="gateway" class="form-control">
                        @if(get_option('gateway_paypal') == 1)
                            <option value="paypal">Paypal</option>
                        @endif
                        @if(get_option('gateway_payu') == 1)
                            <option value="paypal">Payu</option>
                        @endif
                        @if(get_option('gateway_paytm') == 1)
                            <option value="paytm">Paytm</option>
                        @endif
                        @if(get_option('gateway_paystack') == 1)
                            <option value="paystack">Paystack</option>
                        @endif
                        @if(get_option('gateway_razorpay') == 1)
                            <option value="paystack">Razorpay</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2 tab-con">
                    <input type="submit" class="btn btn-custom btn-100-p" value="Pay" name="pay">
                </div>
            </div>
        </form>
    </div>
@endsection
