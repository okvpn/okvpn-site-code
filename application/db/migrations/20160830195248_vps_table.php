<?php

use Phinx\Migration\AbstractMigration;

class VpsTable extends AbstractMigration
{
    const TABLE_NAME = 'vps';

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
            ->addColumn('expiry_date', 'timestamp')
            ->addColumn('activation_date', 'timestamp')
            ->addColumn('price', 'float')
            ->addColumn('provider', 'text', ['null' => true])
            ->addColumn('specifications', 'text', ['null' => true])
            ->addColumn('network', 'text', ['null' => true])
            ->addColumn('code', 'integer')
            ->addColumn('note', 'text', ['null' => true])
            ->addForeignKey('vpn_id', 'vpn_hosts', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addIndex('vpn_id', ['unique' => true])
            ->save();
    }
    

    public function down()
    {
        if ($this->table(self::TABLE_NAME)->exists()) {
            $this->dropTable(self::TABLE_NAME);
        }
    }
}
