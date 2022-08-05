<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS Providers Name
    |--------------------------------------------------------------------------
    |
    */

    'default' => env('SMS_SERVICE_PROVIDER_NAME','sparrow_sms'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers
    |--------------------------------------------------------------------------
    |
    */

    'sms_providers' => [
        'sparrow_sms' => [
            'sms_send_url' => 'http://api.sparrowsms.com/v2/sms/',
            'token'=>env('SMS_API_TOKEN'),
            'sender_name'=>'Allpasal'
        ]

    ]
];
