<?php

return [
    'site' => "https://example.com",
    'mail' => true,
   
    'wallet'  => 'wallet',
    
    /**
     * ReCaptcha 
     */
    'captcha' => [
        'secret'    => 'secret',
        'sitekey'   => 'key',
        'api'       => 'https://www.google.com/recaptcha/api/siteverify',
        'check'     =>  false,
        ],

    /**
     * MailGun config param
     */
    'mailgun' => [
        'key'         => 'key-36281d987d387a436e8fc273b02c465a',
        'secret'      => 'key-8cX7ieq4Zdl',
        'from_email'  => 'noreply@okvpn.org',
        'from_alias'  => 'OkVPN',
    ],
    
    /**
     * Bitpay config param
     */
    'bitpay_token'          => 'bitpay_token',
    'bitpay_private'        => 'private',
    'bitpay_public'         => 'public',
    'notification_url'      => 'https://example.com/notification_url/',
    'redirect_url'          => 'https://example.com/redirect_url/'
];