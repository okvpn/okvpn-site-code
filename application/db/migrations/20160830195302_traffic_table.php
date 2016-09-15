<?php

use Phinx\Migration\AbstractMigration;

class TrafficTable extends AbstractMigration
{
    const TABLE_NAME = 'traffic';

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
            ->addColumn('count', 'float')
            ->addColumn('date', 'timestamp')
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
