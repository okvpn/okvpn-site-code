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
                'id'   => 1,
                'name' => 'pa1',
                'ip'   => 'pa1.ovpn.ovh',
                'location' => 'Панама',
                'icon' => 'public/img/country/pa.png',
                'ordernum' => 10,
                'enable' => 't',
                'free_places' => 99,
                'speedtest' => 's',
            ],
            [
                'id'   => 2,
                'name' => 'uk1',
                'ip'   => 'uk1.ovpn.ovh',
                'location' => 'UK',
                'icon' => 'public/img/country/uk.png',
                'ordernum' => 5,
                'free_places' => 1,
                'enable' => 't',
            ]
        ];
        
        $this->table('vpn_hosts')
            ->insert($rows)
            ->save();
    }
}
