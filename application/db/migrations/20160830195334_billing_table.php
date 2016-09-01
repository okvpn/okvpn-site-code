<?php

use Phinx\Migration\AbstractMigration;

class BillingTable extends AbstractMigration
{
    const TABLE_NAME = 'billing';

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
            ->addColumn('uid', 'integer')
            ->addColumn('amount', 'float')
            ->addColumn('date', 'timestamp')
            ->addColumn('type', 'string', ['limit' => 16])
            ->addForeignKey('uid', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->save();
    }
    
    public function down()
    {
        if ($this->table(self::TABLE_NAME)->exists()) {
            $this->dropTable(self::TABLE_NAME);
        }
    }
}
