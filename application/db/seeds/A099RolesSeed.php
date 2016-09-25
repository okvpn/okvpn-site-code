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
                'id'    => 1,
                'description'  => 'free user account',
                'tag_name' => 'free',
                'traffic_limit' => 16500,
                'hosts_limit' => 1,
                'min_balance' => -0.0001,
                'day_cost' => 0,
                'role_name' => serialize(['USER_ROLE', 'FREE_USER_ROLE'])
            ],
            [
                'id'    => 2,
                'description'  => 'pay user account',
                'tag_name' => 'full',
                'traffic_limit' => 76400,
                'hosts_limit' => 7,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE'])
            ],
            [
                'id'    => 3,
                'description'  => 'admin role',
                'tag_name' => 'admin',
                'traffic_limit' => 76400,
                'hosts_limit' => 1000,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE', 'SUPER_ADMIN_ROLE', 'ADMIN_ROLE'])
            ],
        ];
        
        $this->table('roles')
            ->insert($rows)
            ->save();
    }
}
