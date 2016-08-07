<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
    'localhost' => (object)array(

        'site' => "http://localhost/project/kohana",
        'mail' => true,
        'geoip'=> true,
       
        'wallet'  => '1F43TiHDxJ7S8fF9UmCB1peCurQCKM2nzo',
        'captcha' => (object)array(
            'secret'    => '6Lfx8RYTAAAAAGCPPHkhnSHcw1soijwp0Tbt_k0S',
            'sitekey'   => '6Lfx8RYTAAAAAGz8nElfv0c3VY-4PKqjFViR24V2',
            'api'       => 'https://www.google.com/recaptcha/api/siteverify',
            'check'     => false,
            ),
        'blockchain' => false,
        'secret'     => 'key-8cX7ieq4Zdl',
        'mailkey'    => 'key-36281d987d387a436e8fc273b02c465a',
        ),
    
    //server config 

    'server'  => (object)array(

        'site' => "https://okvpn.org",
        'mail' => true,
        'geoip'=> false,

        'wallet'  => '1F43TiHDxJ7S8fF9UmCB1peCurQCKM2nzo',
        'captcha' => (object)array(
            'secret'    => '6Lfx8RYTAAAAAGCPPHkhnSHcw1soijwp0Tbt_k0S',
            'sitekey'   => '6Lfx8RYTAAAAAGz8nElfv0c3VY-4PKqjFViR24V2',
            'api'       => 'https://www.google.com/recaptcha/api/siteverify',
            'check'     => true
            ),
        'blockchain' => false,
        'secret'     => 'key-8cX7ieq4Zdl',
        'mailkey'    => 'key-36281d987d387a436e8fc273b02c465a',
        ),
    'pof_path' => '/var/www/okvpn/p0f/p0f-mtu-master/11432',
    );