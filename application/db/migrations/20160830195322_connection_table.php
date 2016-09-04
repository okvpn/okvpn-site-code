<?php

use Phinx\Migration\AbstractMigration;

class ConnectionTable extends AbstractMigration
{
    const TABLE_NAME = 'connection';

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
            ->addColumn('date', 'timestamp')
            ->addColumn('type', 'string', ['limit' => 16])
            ->addForeignKey('vpn_id', 'vpn_hosts', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->save();
    }
    
    public function down()
    {
        if ($this->table(self::TABLE_NAME)->exists()) {
            $this->dropTable(self::TABLE_NAME);
        }
    }
}
