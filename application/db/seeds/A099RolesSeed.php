<?php

use Phinx\Seed\AbstractSeed;

class A099RolesSeed extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'id'    => 'free',
                'description'  => 'free user account',
                'tag_name' => 'Free',
                'traffic_limit' => 16500,
                'hosts_limit' => 1,
                'min_balance' => -0.0001,
                'day_cost' => 0,
                'role_name' => serialize(['USER_ROLE', 'FREE_USER_ROLE']),
                'extensions' => serialize(['UPD'])
            ],
            [
                'id'    => 'full',
                'description'  => 'pay user account',
                'tag_name' => 'Full',
                'traffic_limit' => 76400,
                'hosts_limit' => 7,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE']),
                'extensions' => serialize(['UPD', 'TCP'])
            ],
            [
                'id'    => 'admin',
                'description'  => 'admin role',
                'tag_name' => 'admin',
                'traffic_limit' => 76400,
                'hosts_limit' => 1000,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE', 'SUPER_ADMIN_ROLE', 'ADMIN_ROLE']),
                'extensions' => serialize(['UPD', 'TCP'])
            ],
        ];
        
        $this->table('roles')
            ->insert($rows)
            ->save();
    }
}
