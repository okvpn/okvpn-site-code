<?php
        set_time_limit(0);
        $json = file_get_contents(APPPATH.'data/vpn.json');
        $rule = array(
            'id'  => array(null, 'id'),
            'host' => array(null, 'name'),
            'ip' => array(null, 'ip'),
            'location' => array(null, 'location'),
            'free' => array(null, 'free_places'),
            'icon' => array(null, 'icon')
            );
        $array = json_decode($json, true);
        //var_dump($array);
        $i=0;
        foreach ($array as $item) {
            $insert = [];
            $fields = [];
            foreach ($rule as $k => $v) {
                if (reset($v) === null) {
                    $insert[] = $item[$k];
                } else {
                    $insert[] = reset($v);
                }
                $fields[] = end($v);
            }

            DB::insert('vpn_hosts', $fields) 
                ->values($insert)->execute();
        }