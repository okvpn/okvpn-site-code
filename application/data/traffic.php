<?php
        set_time_limit(0);
        $json = file_get_contents(APPPATH.'data/traffic.json');
        $rule = array(
            'uid' => array(null, 'uid'),
            'count' => array(null, 'count'),
            'time' => array(null, 'date')
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

            DB::insert('traffic', $fields) 
                ->values($insert)->execute();
        }