<?php

use Phinx\Seed\AbstractSeed;

class A100UsersSeed extends AbstractSeed
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $rows = [
            [
                'email'   => 'test1@okvpn.org',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-01',
                'checked' => 't',
                'role'    => 'free'
            ],
            [
                'email'   => 'test2@okvpn.org',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe', //123456
                'date'    => '2016-01-02',
                'checked' => 't',
                'role'    => 'free',
                'token'   => '123456'
            ],
            [
                'email'   => 'test3@okvpn.org',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-03',
                'checked' => 'f',
                'role'    => 'full',
                'token'   => '123456'
            ],
            [
                'email'   => 'test4@okvpn.org',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-04',
                'checked' => 't',
                'role'    => 'full'
            ],
            [
                'email'   => 'test5@okvpn.org',
                'pass'    => '$2y$10$QTYAfuYOmTNLcYDVdawpPuunZogLpmENESfudSvqiFsPBfeuqQGFe',
                'date'    => '2016-01-05',
                'checked' => 't',
                'role'    => 'admin'
            ]
        ];
        
        $this->table('users')->insert($rows)->save();
    }
}
