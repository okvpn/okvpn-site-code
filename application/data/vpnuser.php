<?php
        set_time_limit(0);
        $json = file_get_contents(APPPATH.'data/user-vpn.json');
        $rule = array(
            'vpn_id' => array(null, 'vpn_id'),
            'user_id' => array(null, 'user_id'),
            'create' => array(1, 'date_create'),
            'delete' => array(1, 'date_delete'),
            'active' => array(null, 'active'),
            'callback' => array(null, 'callback'),
            'name' => array(null, 'name'),
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
                    $insert[] = (!preg_match('/0000\-00\-00/', $item[$k]))?$item[$k]:date('Y-m-d H:i:s',time()-1000000000);
                }
                $fields[] = end($v);
            }

            DB::insert('vpn_user', $fields) 
                ->values($insert)->execute();
        }