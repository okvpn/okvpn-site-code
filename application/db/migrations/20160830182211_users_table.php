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
            ->addColumn('role', 'integer')
            ->addColumn('promo', 'string', ['limit' => 16, 'null' => true])
            ->addColumn('wallet', 'string', ['limit' => 64, 'null' => true])
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