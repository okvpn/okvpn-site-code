        set_time_limit(0);
        $json = file_get_contents(APPPATH.'data/user-data.json');
        $rule = array(
            'id' => array(null, 'id'),
            'email' => array(null, 'email'),
            'pass' => array(null, 'pass'),
            'date' => array(null, 'date'),
            'last-login' => array(null, 'last_login'),
            'checked-e' => array(null, 'checked'),
            'role' => array(null, 'role')
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

            DB::insert('users', $fields) 
                ->values($insert)->execute();
        }