<?php 
use App\DB;
use Symfony\Component\Yaml\Yaml;

require __DIR__.'/../autoload.php';

function ip_lookup($file) {
    $data = file_get_contents($file);
    $data = preg_split('/\n/', $data);
    foreach ($data as $item) {
        $item = preg_split('/\,/', $item);
        if (!isset($item[0]) || !isset($item[1])) {
            continue;
        }
        $item[1] = preg_replace('/\s/','', $item[1]);
        $ans[$item[1]] = $item[0];
    }
    return is_null($ans)?false:$ans;
}

$content = file_get_contents(__DIR__.'/config/config.yml');

if ($content === false) {
    die();   
}

$conf = Yaml::parse($content);

$handle  = fopen(__DIR__.'/'.$conf['file_dump'] ,'r');
$traffic =  array();
$host = $conf['host'];

if ($handle) {

    while (($item = fgets($handle))) {
        $item = preg_split('/\x20/', $item);
        $ln = count($item);
        if (isset($item[$ln-2]) && $item[$ln-2] == 'length' && is_int((int)end($item))) {

            if (isset($item[2]) && preg_match_all("/$host/", $item[2], $hst)) {
                if (array_key_exists($hst[0][0], $traffic)) {
                    $traffic[$hst[0][0]]+= end($item);
                } else {
                    $traffic[$hst[0][0]] = (int)end($item);
                }

            } elseif (isset($item[4]) && preg_match_all("/$host/", $item[4], $hst)) {

                if (array_key_exists($hst[0][0], $traffic)) {
                    $traffic[$hst[0][0]]+= end($item);
                } else {
                    $traffic[$hst[0][0]] = (int)end($item);
                }
            }
        }
    }
}

$history = @file_get_contents(__DIR__.'/'.$conf['file_save']);
if (!($history and $history = unserialize($history))) {
    $history = array();
}

foreach ($traffic as $hst => $value) {
    if (array_key_exists($hst, $history)) {
        $history[$hst]+= $value;
    } else {
        $history[$hst] = $value;
    }
}

if (!empty($history)) {
    file_put_contents(__DIR__.'/'.$conf['file_save'], serialize($history));
}

if (round(time()/60)%$conf['report'] == 1) {

    $host = ip_lookup($conf['ip_lookup']);
    $history = @file_get_contents(__DIR__.'/'.$conf['file_save']);

    if (!($host && $history and $history = unserialize($history))) {
        die;
    }

    foreach ($history as $ip => $val) {
        if (array_key_exists($ip, $host)) {
            $data[$host[$ip]] = $val;
        }
    }

    if (!is_null($data)) {
        var_dump($data);
        $data = base64_encode(json_encode($data));
        
        $ch = curl_init($conf['report_host']);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array('data' => $data)),
            CURLOPT_SSL_VERIFYPEER => false,
            ));

        if (curl_exec($ch)) {
            unlink(__DIR__.'/'.$conf['file_save']);
        }
    }

}