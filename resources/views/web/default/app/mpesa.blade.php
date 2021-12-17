<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/default/vendor/bootstrap/css/bootstrap.min.css"/>
    <script type="application/javascript" src="/assets/default/vendor/jquery/jquery.min.js"></script>
    <title>MPESA</title>
    <style>
        body{
           background-color: #fdfdfe;
        }
        .box{
            background-color: rgba(0,0,0,0.2);
            margin: 10px auto 0;
            padding: 20px;
            padding-bottom: 0;
            height: auto;
            border-radius: 10px;
            border: 2px solid rgba(0,0,0,0.1);
            box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
        }
        .loader,
        .loader:before,
        .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation: load7 1.8s infinite ease-in-out;
            animation: load7 1.8s infinite ease-in-out;
        }
        .loader {
            color: #ffffff;
            font-size: 10px;
            margin: 80px auto;
            position: relative;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }
        .loader:before,
        .loader:after {
            content: '';
            position: absolute;
            top: 0;
        }
        .loader:before {
            left: -3.5em;
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }
        .loader:after {
            left: 3.5em;
        }
        @-webkit-keyframes load7 {
            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }
            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }
        @keyframes load7 {
            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }
            40% {
                box-shadow: 0 2.5em 0 0;
            }

        }
    </style>
</head>
<body>
    <div class="container-fluid">
        @if($mode == 'pay')
            <form method="post" action="/api/v1/mpesa/verify">
                <input type="hidden" name="amount" value="{!! $transaction->price ?? 0 !!}">
                <input type="hidden" name="id" value="{!! $transaction->id ?? '' !!}">
                <input type="hidden" name="mode" value="{!! $type ?? '' !!}">
                <div class="row">
                    <div class="col-12 col-xs-12 col-md-4 col-md-offset-4">
                        <div class="box">
                            <div class="form-group">
                                <label>Enter Your Phone Number:</label>
                                <input type="tel" name="phone" class="form-control text-center">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Pay">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
        @if($mode == 'verify')
            <div class="row">
                <div class="col-12 col-xs-12 col-md-4 col-md-offset-4">
                    <div class="box" style="text-align: center">
                        <b>Waiting For User Payment</b>
                        <div class="loader">Loading...</div>
                        <br>
                    </div>
                </div>
            </div>
            <script>
                setInterval(function () {
                    $.ajax({
                        method:'GET',
                        url: '/api/v1/mpesa/ajax/{!! $id !!}/{!! $type !!}',
                        success:function (data) {
                            if(data == '1'){
                                if('{!! $type !!}' == 'product'){
                                    return window.location.href = '{!! url('/') !!}/product/{!! $Transaction->content_id !!}'
                                }
                                if('{!! $type !!}' == 'wallet'){
                                    return window.location.href = '/user/balance/charge';
                                }
                            }
                        }
                    })
                },5000)

            </script>
        @endif
    </div>
</body>
</html>
