<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('main.print') }} {{ !empty($title) ? $title : '' }}</title>
    <link rel="stylesheet" href="/assets/default/stylesheets/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/default/stylesheets/vendor/bootstrap/css/bootstrap-3.2.rtl.css">
    <link rel="stylesheet" href="/assets/default/stylesheets/view-custom.css">
</head>
<body>
<div class="container main-box">
    <div class="row bb2s">
        <div class="col-md-9 col-xs-12 factor-logo-container">
            <div class="row">
                <div class="h-20"></div>
                <div class="col-md-4 col-xs-12 text-right">
                    <img src="{{ get_option('factor_watermark','') }}"/>
                </div>
                <div class="col-md-8 col-xs-12 text-center">
                    <h3 class="mtop5">{{ trans('main.invoice') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 br2">
            <div class="h-15"></div>
            <span>{{ trans('main.invoice_number') }}:</span>&nbsp;<label>{{ $item->transaction_id }}</label>
            <div class="h-10"></div>
            <span>{{ trans('main.invoice_date') }}:</span>&nbsp;<label>{{ date('d F Y - H:i',$item->created_at) }}</label>
        </div>
    </div>
    <div class="row factor-divider text-center">
        <span>{{ trans('main.seller_info') }}</span>
    </div>
    <div class="row bb2s">
        <div class="col-md-8 col-xs-12">
            <div class="row bb2s">
                <div class="col-md-8">
                    <div class="h-5"></div>
                    <label>{{ trans('main.name') }}:</label>
                    <span>{{ get_option('site_title') }}</span>
                </div>
                <div class="col-md-4 br1">
                    <div class="h-5"></div>
                    <label>{{ trans('main.reg_number') }}:</label>
                </div>
            </div>
            <div class="row bb2s">
                <div class="col-md-8">
                    <div class="h-5"></div>
                    <label>{{ trans('main.province') }}:</label>
                    <span>{{ trans('main.your_province') }}</span>
                </div>
                <div class="col-md-4 br1">
                    <div class="h-5"></div>
                    <label>{{ trans('main.city') }}:</label>
                    <span>{{ trans('main.your_city') }}</span>
                </div>
            </div>
            <div>
                <div class="h-5"></div>
                <label>{{ trans('main.address') }}</label>
                <span>{{ trans('main.your_address') }}</span>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="row br1">
                <div class="bb2s prr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.email') }}:</label>
                    <span>{{ get_option('site_email') }}</span>
                </div>
                <div class="bb2s prr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.zip_code') }}</label>
                    <span></span>
                </div>
                <div class="prr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.phone_number') }}:</label>
                    <span>{{ get_option('site_phone') }}</span>
                </div>
            </div>

        </div>
    </div>
    <div class="row factor-divider text-center">
        <span>{{ trans('main.customer_info') }}</span>
    </div>
    <div class="row bb2s">
        <div class="col-md-8 col-xs-12">
            <div class="row bb2s">
                <div class="col-md-8">
                    <div class="h-5"></div>
                    <label>{{ trans('main.name') }}:</label>
                    <span>{{ $user['name'] }}</span>
                </div>
                <div class="col-md-4 br1">
                    <div class="h-5"></div>
                    <label>{{ trans('main.reg_number') }}</label>
                </div>
            </div>
            <div class="row bb2s">
                <div class="col-md-8">
                    <div class="h-5"></div>
                    <label>{{ trans('main.province') }}:</label>
                    <span>{{ !empty($userMeta['state']) ? $userMeta['state'] : '' }}</span>
                </div>
                <div class="col-md-4 br1">
                    <div class="h-5"></div>
                    <label>{{ trans('main.city') }}:</label>
                    <span>{{ !empty($userMeta['city']) ? $userMeta['city'] : '' }}</span>
                </div>
            </div>
            <div>
                <div class="h-5"></div>
                <label>{{ trans('main.address') }}:</label>
                <span>{{ !empty($userMeta['address']) ? $userMeta['address'] : '' }}</span>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="row br1">
                <div class="bb2s brr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.email') }}</label>
                    <span>{{ $user['email'] }}</span>
                </div>
                <div class="bb2s prr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.zip_code') }}:</label>
                    <span>{{ !empty($userMeta['postalcode']) ? $userMeta['postalcode'] : '' }}</span>
                </div>
                <div class="prr15">
                    <div class="h-5"></div>
                    <label>{{ trans('main.phone_number') }}:</label>
                    <span>{{ !empty($userMeta['phone']) ? $userMeta['phone'] : '' }}</span>
                </div>
            </div>

        </div>
    </div>
    <div class="row factor-divider text-center">
        <span>{{ trans('main.purchased_items') }}</span>
    </div>
    <div class="row bb2s">
        <table class="table-responsive">
            <thead>
            <th>#</th>
            <th>{{ trans('main.product') }}</th>
            <th>{{ trans('main.amount') }} ({{ currencySign() }})</th>
            <th>{{ trans('main.tax_fee') }}</th>
            <th>{{ trans('main.total_price') }}</th>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>{{ !empty($item->content->title) ? $item->content->title : 'Unknown Product' }}</td>
                <td>{{ contentMeta($item->content->id,'price',0) }}</td>
                <td>۰</td>
                <td>{{ $item->transaction->price }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row bb2s">
        <div class="col-md-6 col-xs-12">
            <div class="h-5"></div>
            <label class="pull-left">{{ trans('main.payment_method') }}:</label>
            <span>{{ trans('main.gateway') }}</span>
        </div>
        <div class="col-md-6 col-xs-12 br2">
            <div class="h-5"></div>
            <label class="pull-left">{{ trans('main.delivery_type') }}:</label>
            <span>{{ trans('main.download') }}</span>
        </div>
    </div>
    <div class="row bb2s">
        <div class="col-md-6 col-xs-12">
            <div class="h-5"></div>
            <label>{{ trans('main.additional_description') }}</label>
            <span class="pull-left"></span>
        </div>
        <div class="col-md-6 col-xs-12 br2">
            <div class="h-5"></div>
            <label class="pull-left">{{ trans('main.return_refund') }}</label>
            <span><a href=""></a></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="h-5"></div>
            <label class="pull-left">{{ trans('main.email') }}:</label>
            <span>{{ get_option('site_email') }}</span>
        </div>
        <div class="col-md-4 col-xs-12 br2">
            <div class="h-5"></div>
            <label class="pull-left">{{ trans('main.phone_number') }}</label>
            <span>{{ get_option('site_phone') }}</span>
        </div>
        <div class="col-md-4 col-xs-12 br2">
            <div class="h-5"></div>
            <label>{{ trans('main.support') }}</label>
            <span class="pull-left"><a href=""></a></span>
        </div>
    </div>
</div>
</body>
</html>
