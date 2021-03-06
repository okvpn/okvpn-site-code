<?php

use Phinx\Migration\AbstractMigration;

class UsersTable extends AbstractMigration
{

    const TABLE_NAME = 'users';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = $this->table(self::TABLE_NAME);

        if ($table->exists()) {
            return;
        }

        $table->addColumn('email', 'string', ['limit' => 64])
            ->addColumn('pass', 'string', ['limit' => 96])
            ->addColumn('date', 'timestamp')
            ->addColumn('last_login', 'timestamp', ['null' => true])
            ->addColumn('checked', 'boolean')
            ->addColumn('role', 'string', ['limit' => 32])
            ->addColumn('token', 'string', ['limit' => 64, 'null' => true])
            ->addIndex('email', ['unique' => true])
            ->save();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}