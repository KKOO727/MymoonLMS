<?php
return [
    'client_id'                 => env('PAYPAL_CLIENT_ID','AdoWUyC98-3jivF6XeSoqq0KtTP-GzU2F9DD94BbdyLnaLMbGAbyw38ZkCg_Xkf5yxMzMtIHwZ93dy99'),
    'secret'                    => env('PAYPAL_SECRET','EFvcmW0a-pR96mvrYPjotU28y1vcbsDib_-nmcAMr0ChqY1Zcq2NqkJ_W0KRD4SrSWpLAGGjDGaEY713'),
    'settings'                  => array(
        'mode'                  => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut'=> 30,
        'log.LogEnabled'        => true,
        'log.FileName'          => storage_path() . '/logs/paypal.log',
        'log.LogLevel'          => 'ERROR'
    ),
];
