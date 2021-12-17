<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('admin.print') }} {{ $item->title }}</title>
    <link rel="stylesheet" href="/assets/stylesheets/view-custom.css">
    <style>
        .main-box {
            width: 90%;
            height: auto;
            overflow: hidden;
            border: 2px solid #999;
            min-height: 200px;
            margin: 20px auto 0;
            font-family: Tahoma, Arial, Helvetica, sans-serif;
        }

        .factor-logo-container {
            text-align: center;
            height: auto;
            min-height: 100px;
            float: right;
            height: auto;
            width: 60%;
            padding-top: 15px;
        }

        table {
            width: 90%;
            margin: 0px auto 0px;
            font-size: .8em;
        }

        table th {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        input {
            font-family: Tahoma, Arial, Helvetica, sans-serif;
            font-size: 1em;
        }
    </style>
</head>
<body>
<div class="main-box">
    <div style="width: 20%;height: 100px;float: right"></div>
    <div class="factor-logo-container">
        <img src="{{ get_option('factor_watermark','') }}"/>
        <br>
        <h3 style="margin-top: 5px">{{ trans('admin.financial_documents') }}</h3>
    </div>
    <div style="width: 20%;text-align: left;float: left;padding-top: 25px;padding-left:25px;font-size: .8em">
        <div>
            <span>{{ trans('admin.document_number') }}:</span>&nbsp;<label>{{ $item->id }}</label>
        </div>
        <div style="margin-top: 10px;">
            <span>{{ trans('admin.th_date') }}:</span>&nbsp;<label>{{ date('d F Y - H:i') }}</label>
        </div>
    </div>
    <div style="width: 100%;clear: both;"></div>
    <table>
        <thead>
        <th>{{ trans('admin.username') }}</th>
        <th>{{ trans('admin.th_title') }}</th>
        <th>{{ trans('admin.document_type') }}</th>
        <th>{{ trans('admin.amount') }}({{ trans('admin.cur_dollar') }})</th>
        </thead>
        <tbody>
        <tr>
            <th>{{ !empty($item->user->name) ? $item->user->name : 'Platform Account' }}</th>
            <th>{{ $item->title }}</th>
            <th>@if($item->type == 'add') {{ trans('admin.addiction') }} @else {{ trans('admin.deduction') }} @endif</th>
            <th>{{ $item->price }}</th>
        </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <th style="text-align: right;min-height: 100px;">
                {{ !empty($item->description) ? $item->description : 'Description' }}
            </th>
        </tr>
        </tbody>
    </table>
    <div style="clear: both;width: 100%;height: 40px;"></div>
    <div style="width: 90%;margin: 0 auto 0">

        <div style="width: 33%;float: right">
            <span>{{ trans('admin.created_by') }}:</span>&nbsp;<label>{{ !empty($item->exporter->name) ? $item->exporter->name : 'Automatic' }}</label>
        </div>
        <div style="width: 33%;float: right">
            <span>{{ trans('admin.approved_by') }}:</span>&nbsp;<input type="text" value="{{ get_option('factor_seconder','') }}" style="border:none;background: transparent;">
        </div>
        <div style="width: 33%;float: right">
            <span>{{ trans('admin.financial_manager') }}:</span>&nbsp;<input type="text" value="{{ get_option('factor_approver','') }}" style="border:none;background: transparent;">
        </div>

    </div>

    <div style="clear: both;width: 100%;height: 40px;"></div>
</div>
</body>
</html>
