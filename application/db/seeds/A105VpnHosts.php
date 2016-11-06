<?php

use Phinx\Seed\AbstractSeed;

class A105VpnHosts extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'name' => 'pa1',
                'ip'   => 'pa1.ex.com',
                'location' => 'Панама',
                'icon' => 'public/img/country/pa.png',
                'ordernum' => 10,
                'enable' => 't',
                'free_places' => 99,
                'speedtest' => 's',
            ],
            [
                'name' => 'uk1',
                'ip'   => 'uk1.ex.com',
                'location' => 'UK',
                'icon' => 'public/img/country/uk.png',
                'ordernum' => 5,
                'free_places' => 1,
                'enable' => 't',
            ]
        ];
        
        $this->table('vpn_hosts')->insert($rows)->save();
    }
}
