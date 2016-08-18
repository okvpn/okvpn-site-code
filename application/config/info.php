<?php

return [
    'site' => "http://localhost/project/kohana",
    'mail' => true,
   
    'wallet'  => '1F43TiHDxJ7S8fF9UmCB1peCurQCKM2nzo',
    
    /**
     * ReCaptcha 
     */
    'captcha' => [
        'secret'    => '6Lfx8RYTAAAAAGCPPHkhnSHcw1soijwp0Tbt_k0S',
        'sitekey'   => '6Lfx8RYTAAAAAGz8nElfv0c3VY-4PKqjFViR24V2',
        'api'       => 'https://www.google.com/recaptcha/api/siteverify',
        'check'     => false,
        ],

    /**
     * MailGun config param
     */
    'mailgun_secret'        => 'key-8cX7ieq4Zdl',
    'mailgun_key'           => 'key-36281d987d387a436e8fc273b02c465a',
    
    /**
     * Bitpay config param
     */
    'bitpay_token'          => 'bitpay_token',
    'bitpay_private'        => 'bitpay/bitpay.pri',
    'bitpay_public'         => 'bitpay/bitpay.pub',
    'notification_url'      => 'https://okvpn.org/user/notification_bitpay/',
    'redirect_url'          => 'https://okvpn.org/user/redirect_url/'
];