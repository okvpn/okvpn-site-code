<?php

use Phinx\Migration\AbstractMigration;

class UserRoles extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('roles');
        $table
            ->addColumn('description', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('tag_name', 'string', ['limit' => 32])
            ->addColumn('traffic_limit', 'float')
            ->addColumn('day_cost', 'float')
            ->addColumn('hosts_limit', 'integer')
            ->addColumn('min_balance', 'float')
            ->addColumn('role_name', 'string', ['limit' => 512, 'null' => true])
            ->save();

        $refTable = $this->table('users');
        $refTable
            ->addForeignKey('role', 'roles', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->save();
    }

    public function down()
    {
        $refTable = $this->table('users');
        $refTable->dropForeignKey('role');
        $this->dropTable('roles');
    }

}
