<?php

return [
    'site' => "https://example.com",
    'mail' => true,
   
    'wallet'  => 'wallet',
    
    /**
     * ReCaptcha 
     */
    'captcha' => [
        'secret'    => '6Lfx8RYTAAAAAGCPPHkhnSHcw1soijwp0Tbt_k0S',
        'sitekey'   => '6Lfx8RYTAAAAAGz8nElfv0c3VY-4PKqjFViR24V2',
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
        'site'        => 'okvpn.org',
    ],

    'database' => [
        'default' => [
            'type'       => 'PostgreSQL',
            'connection' => [
                /**
                 * There are two ways to define a connection for PostgreSQL:
                 *
                 * 1. Full connection string passed directly to pg_connect()
                 *
                 * string   info
                 *
                 * 2. Connection parameters:
                 *
                 * string   hostname    NULL to use default domain socket
                 * integer  port        NULL to use the default port
                 * string   username
                 * string   password
                 * string   database
                 * boolean  persistent
                 * mixed    ssl         TRUE to require, FALSE to disable, or 'prefer' to negotiate
                 *
                 * @link http://www.postgresql.org/docs/current/static/libpq-connect.html
                 */
                'hostname'   => '%host%',
                'username'   => '%username%',
                'password'   => '%password%',
                'persistent' =>  false,
                'database'   => '%database%',
            ],
            
            'primary_key'  => '',   // Column to return from INSERT queries, see #2188 and #2273
            'schema'       => '',
            'table_prefix' => '',
            'charset'      => 'utf8',
            'caching'      =>  false,
        ]
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