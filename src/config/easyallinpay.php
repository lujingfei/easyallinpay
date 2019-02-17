<?php
return [
    'default' => [
        'mch_id' => env('ALLINPAY_MCH_ID', ''),
        'appid' => env('ALLINPAY_APPID', ''),
        'mch_key' => env('ALLINPAY_MCH_KEY', ''),
        'pay_type' => env('ALLINPAY_PAY_TYPE', 'W02'),
        'sign_type' => 'MD5',
        'rsa_public_key' => env('ALLINPAY_RSA_PLANTFORM_PUBLIC_KEY'),
        'rsa_private_key' => env('ALLINPAY_RSA_PRIVATE_KEY'),
        'sub_appid' => env('ALLINPAY_SUB_APPID', ''),
        'notify_url' => env('ALLINPAY_NOTIFY_URL', ''),
    ],
];