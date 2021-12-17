<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify</title>
</head>
<body>
<div style="text-align: center;margin: 20px auto 0;">
    @if(!empty(request()->get('gateway')) && request()->get('gateway') == 'credit')
        @if(request()->get('mode') == 'failed')
            @if(request()->get('type') == 'nocredit')
                <h4>{!! trans('main.no_credit') !!}</h4>
                <div style="height: 20px;"></div>
                <a href="pa://nocredit">Go to application</a>
            @endif
        @endif
    @endif
    @if(empty(request()->get('gateway')) || request()->get('gateway') != 'credit')
        @if(request()->get('mode') == 'failed')
            <h4>{!! trans('main.failed') !!}</h4>
            <div style="height: 20px;"></div>
            <a href="pa://failed">Go to application</a>
        @endif
        @if(request()->get('mode') == 'successfully')
            <h4>{!! trans('main.successful') !!}</h4>
            <div style="height: 20px;"></div>
            <a href="pa://successfully">Go to application</a>
        @endif
    @endif
</div>
</body>
</html>
