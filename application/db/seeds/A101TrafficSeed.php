<?php

use Phinx\Seed\AbstractSeed;

class A101TrafficSeed extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'uid' => 2,
                'count' => 233.33,
                'date' => date('Y-m-d H:i:s', time()-100000)
            ],
            [
                'uid' => 2,
                'count' => 433.33,
                'date' => date('Y-m-d H:i:s')
            ],
            [
                'uid' => 2,
                'count' => 233.33,
                'date' => date('Y-m-d H:i:s', time()-100000)
            ],
            [
                'uid' => 4,
                'count' => 77233.33,
                'date' => date('Y-m-d H:i:s', time()-100000)
            ],
        ];

        $this->table('traffic')
            ->insert($rows)
            ->save();
    }
}

