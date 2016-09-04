<?php

use Phinx\Seed\AbstractSeed;

class A111BillingSeed extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'uid' => 3,
                'amount' => 0.2,
                'type' => 'real',
                'date' => '2016-01-01'
            ],
        ];

        $this->table('billing')
            ->insert($rows)
            ->save();
    }
}
