<?php

use Phinx\Migration\AbstractMigration;

class UserRoles extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('roles');
        $table->addColumn('description', 'string', array('limit' => 128, 'null' => true))
            ->addColumn('traffic_limit', 'float')
            ->addColumn('day_cost', 'float')
            ->addColumn('hosts_limit', 'integer')
            ->addColumn('min_balance', 'float')
            ->addColumn('role_name', 'string', array('limit' => 512, 'null' => true))
            ->save();

        $rows = [
            [
                'id'    => 1,
                'description'  => 'free user account',
                'traffic_limit' => 16500,
                'hosts_limit' => 1,
                'min_balance' => -0.0001,
                'day_cost' => 0,
                'role_name' => serialize(['USER_ROLE', 'FREE_USER_ROLE'])
            ],
            [
                'id'    => 2,
                'description'  => 'pay user account',
                'traffic_limit' => 76400,
                'hosts_limit' => 7,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE'])
            ],
            [
                'id'    => 3,
                'description'  => 'admin role',
                'traffic_limit' => 76400,
                'hosts_limit' => 1000,
                'day_cost' => 0.0334,
                'min_balance' => 0.0001,
                'role_name' => serialize(['USER_ROLE', 'PAY_USER_ROLE', 'SUPER_ADMIN_ROLE', 'ADMIN_ROLE'])
            ],
        ];
        $table->insert($rows)
            ->saveData();

        $refTable = $this->table('users');
        $refTable
            ->addForeignKey('role', 'roles', 'id', array('delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'))
            ->save();
    }

    public function down()
    {
        $refTable = $this->table('users');
        $refTable->dropForeignKey('role');
        $this->dropTable('roles');
    }

}
