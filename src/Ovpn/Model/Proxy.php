<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Proxy extends ORM {

    protected $_table_name = 'proxy';

    public function scanFirstProxy()
    {
        $proxy =  $this->getFirstScan();

        if ($proxy->loaded()) {

            $start = microtime(true);
            $time  = time();

            $ch = curl_init("http://jurasikt.u-host.in/?v=$time");
            curl_setopt_array($ch, array(
                    CURLOPT_PROXY          => $proxy->host,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 11
                ));
            $response = curl_exec($ch);
            $json = json_decode($response);
            $timeResponse = microtime(true)-$start;
            if (isset($json->hash) && $json->hash == md5($time)) {
                //echo $json->hash;
                $proxy->lastcheck = true;
                $proxy->uptime = 
                    ($proxy->uptime * $proxy->count + 1)/($proxy->count + 1);
                $proxy->ping = ($proxy->ping * $proxy->count + $timeResponse) /
                    ($proxy->count + 1);
                
            } else {
                $proxy->lastcheck = false;
                $proxy->uptime = 
                    ($proxy->uptime * $proxy->count)/($proxy->count + 1);                
            }
            $proxy->lasttime = date('Y-m-d H:i:s');
            $proxy->lastping = $timeResponse;
            $proxy->count += 1;
            $proxy->save();
        }
    }

    public function getProxies()
    {
        $proxies = $this->where('lastcheck', '=', true);
        $param = Validation::factory($_GET);

        $param
            ->rule('limit', 'digit')
            ->rule('uptime' ,'digit')
            ->rule('waiting', 'digit');

        if (!$param->check()) {
            return ['bad request'];
        }
        $get = $_GET;

        if (array_key_exists('limit', $get)) {
            $proxies->limit($get['limit']);
        } else {
            $proxies->limit(1000);
        }

        if (array_key_exists('code_country', $get)) { // example RU
            $codes = explode(',', $get['code_country']);

            $proxies->where_open()
                ->where('region','=', current($codes));
            foreach ($codes as $cod) {
                $proxies->or_where('region','=', $cod);
            }
            $proxies->where_close();
        }

        if (array_key_exists('uptime', $get)) { // in procent
            $proxies->and_where('uptime','>',$get['uptime']/100);
        }

        if (array_key_exists('waiting', $get)) { // in millisecond
            $proxies->and_where('ping','<',$get['waiting']/1000);
        }

        $proxies->order_by('lasttime', 'DESC');
        $pxlist = [];
        foreach ($proxies->find_all() as $prx) {
            $pxlist[] = array(
                'ip'        => $prx->host,
                'country'   => $prx->country,
                'waiting'   => round($prx->ping,3),
                'uptime'    => 100*round($prx->uptime,3),
                'lastcheck' => $prx->lasttime,
                );
        }
        return $pxlist;

    }

    private function getFirstScan()
    {
        return $this->order_by('lasttime', 'ASC')
            ->limit(1)->find();
    }
}