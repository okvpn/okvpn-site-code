<?php

use Phinx\Seed\AbstractSeed;

class A100UsersSeed extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'email'   => 'test1',
                'pass'    => 'pass',
                'date'    => '2016-01-01',
                'checked' => 'f',
                'role'    => 1
            ],
            [
                'email'   => 'test2',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe', //123456
                'date'    => '2016-01-02',
                'checked' => 't',
                'role'    => 1,
                'token'   => '123456'
            ],
            [
                'email'   => 'test3',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-03',
                'checked' => 't',
                'role'    => 2,
                'token'   => '123456'
            ],
            [
                'email'   => 'test4',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-04',
                'checked' => 't',
                'role'    => 2
            ],
            [
                'email'   => 'test5',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-05',
                'checked' => 't',
                'role'    => 3
            ]
        ];
        
        $this->table('users')
            ->insert($rows)
            ->save();
    }
}
