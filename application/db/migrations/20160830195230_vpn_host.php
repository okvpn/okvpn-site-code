<?php

use Phinx\Migration\AbstractMigration;

class VpnHost extends AbstractMigration
{
    const TABLE_NAME = 'vpn_hosts';

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
            ->addColumn('name', 'string', ['limit' => 8])
            ->addColumn('ip', 'string', ['limit' => 32])
            ->addColumn('location', 'string', ['limit' => 32])
            ->addColumn('free_places', 'integer')
            ->addColumn('icon', 'string', ['limit' => 64])
            ->addColumn('ordernum', 'integer', ['null' => true])
            ->addColumn('enable', 'boolean')
            ->addColumn('speedtest', 'string', ['limit' => 64, 'null' => true])
            ->save();
    }

    
    public function down()
    {
        if ($this->table(self::TABLE_NAME)->exists()) {
            $this->dropTable(self::TABLE_NAME);
        }
    }
}
