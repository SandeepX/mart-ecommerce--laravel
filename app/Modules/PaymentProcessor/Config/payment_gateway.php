<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS Providers Name
    |--------------------------------------------------------------------------
    |
    */

    'default' => env('PAYMENT_GATEWAY_NAME','connect_ips'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers
    |--------------------------------------------------------------------------
    |
    */

    'clients' => [
        'connect_ips' => [
            'api' => env('CONNECT_IPS_API_MODE','development'),
            'development'=>[
                'gateway_url' => 'https://uat.connectips.com/connectipswebgw/loginpage',
                'merchant_id'=>'428',
                'app_id'=>'MER-428-APP-1',
                'app_name'=>'Allpasal',
                'payment_validation_url' => 'https://uat.connectips.com/connectipswebws/api/creditor/validatetxn',
                'username_for_validation'=>'MER-428-APP-1',
                'password_for_validation'=>'Abcd@123',
                'password_for_creditor_pfx'=>'123',
                'creditor_pfx_path' => app_path() . '/Modules/PaymentProcessor/storage/connectips/CREDITOR.pfx',
                //'creditor_pfx_path' => public_path('connectips/CREDITOR.pfx'),
                'transaction_currency' => "NPR"
            ],
            'production' => [
                'gateway_url' => 'https://login.connectips.com/connectipswebgw/loginpage',
                'merchant_id'=>'650',
                'app_id'=>'MER-650-APP-1',
                'app_name'=>'All Pasal Public Limited',
                'payment_validation_url' => 'https://login.connectips.com/connectipswebws/api/creditor/validatetxn',
                'username_for_validation'=>'MER-650-APP-1',
                'password_for_validation'=>'@llp@$aL',
                'password_for_creditor_pfx'=>'@llp@$aL',
                'creditor_pfx_path' => app_path() . '/Modules/PaymentProcessor/storage/connectips/ALLPASAL.pfx',
                //'creditor_pfx_path' => public_path('connectips/ALLPASAL.pfx'),
                'transaction_currency' => "NPR"
            ]
        ]

    ]
];
