<?php

use Phinx\Seed\AbstractSeed;

class A107VpnUser extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'vpn_id' => 1,
                'user_id' => 2,
                'date_create' => '2016-01-30',
                'date_delete' => '2017-01-30',
                'active' => 't',
                'callback' => '123',
                'name' => '123'
            ],
        ];
        
        $this->table('vpn_user')
            ->insert($rows)
            ->save();
    }
}
