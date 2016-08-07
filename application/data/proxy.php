<?php 

        set_time_limit(0);
        $json = file_get_contents(APPPATH.'data/proxy.json');
        $rule = array(
            'host' => null,
            'country' => null,
            'region' => null,
            'uptime' => null,
            'count' => null,
            'lasttime' => null,
            'lastcheck' => null,
            'lastping' => null,
            'ping' => null,
            'used' => null,
            'city' => null
            );
        $array = json_decode($json, true);
        //var_dump($array);
        foreach ($array as $item) {
            $insert = [];
            foreach ($rule as $k => $v) {
                if ($v === null) {
                    $insert[] = $item[$k];
                } else {
                    $insert[] = $v;
                }
                
            }
            DB::insert('proxy', array_keys($rule)) 
                ->values($insert)->execute();
        }