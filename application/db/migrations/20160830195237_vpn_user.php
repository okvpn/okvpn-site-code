<?php

use Phinx\Migration\AbstractMigration;

class VpnUser extends AbstractMigration
{
    const TABLE_NAME = 'vpn_user';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = $this->table(self::TABLE_NAME);

        if ($table->exists()) {
            return;
        }
        
        $table
            ->addColumn('vpn_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('date_create', 'timestamp')
            ->addColumn('date_delete', 'timestamp')
            ->addColumn('active', 'boolean')
            ->addColumn('callback', 'string', ['limit' => 32])
            ->addColumn('name', 'string', ['limit' => 24])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addForeignKey('vpn_id', 'vpn_hosts', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_NAME)->exists()) {
            $this->dropTable(self::TABLE_NAME);
        }
    }
}
