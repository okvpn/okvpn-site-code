#!/usr/bin/php
<?php
use Symfony\Component\Yaml\Yaml;
require __DIR__.'/../autoload.php';

$content = file_get_contents(__DIR__.'/config/config.yml');

if ($content === false) {
    die();   
}

$conf = Yaml::parse($content);

$ch = curl_init($conf['report_connect']);
curl_setopt_array($ch, array(
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array(
        'name' => getenv('X509_0_CN'),
        'type' => 'disconnect',
        )),
    CURLOPT_SSL_VERIFYPEER => false,
    ));

curl_exec($ch);

/*
client-connect /var/www/src/Radius/connect.php
client-disconnect /var/www/src/Radius/disconnect.php
*/