<?php

return [
    'site' => "https://example.com",
    'mail' => true,
    'vpn_domain' => '%vpn_domain_name%',
    
    /**
     * ReCaptcha 
     */
    'captcha' => [
        'secret'    => '%google_captcha_key%',
        'sitekey'   => '%google_captcha_id%',
        'api'       => 'https://www.google.com/recaptcha/api/siteverify',
        'check'     =>  true,
    ],

    'mailer' => [
        'username' => '%mailer_username%',
        'password' => '%mailer_password%',
        'transport_host' => 'smtp.mailgun.org',
        'transport_port' => '25',
        'sender' => 'noreply@okvpn.org',
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
    ]
];
