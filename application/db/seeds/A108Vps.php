<?php

use Phinx\Seed\AbstractSeed;

class A108Vps extends AbstractSeed
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $rows = [
            [
                'vpn_id' => 1,
                'expiry_date' => '2016-11-01',
                'activation_date' => '2016-01-01',
                'price' => 0,
                'specifications' => $this->getSpecification(),
                'network' => $this->getNetwork(),
                'code' => 1
            ],
            [
                'vpn_id' => 2,
                'expiry_date' => '2016-11-01',
                'activation_date' => '2016-01-01',
                'price' => 0,
                'specifications' => $this->getSpecification(),
                'network' => $this->getNetwork(),
                'code' => 1
            ],
        ];

        $this->table('vps')
            ->insert($rows)
            ->save();
    }

    private function getSpecification()
    {
        return <<<TXT
Ubuntu 14.04.1 LTS 
CPU Info:
processor       : 0
vendor_id       : GenuineIntel
cpu family      : 6
model           : 63
model name      : Intel(R) Xeon(R) CPU E5-2650 v3 @ 2.30GHz
stepping        : 2
microcode       : 0x31
cpu MHz         : 2299.998
cache size      : 25600 KB
physical id     : 0
siblings        : 1
core id         : 0
cpu cores       : 1
apicid          : 0
initial apicid  : 0
fpu             : yes
fpu_exception   : yes
cpuid level     : 15
wp              : yes
bogomips        : 4599.99
clflush size    : 64
cache_alignment : 64
address sizes   : 40 bits physical, 48 bits virtual
copied max      : 755 MB/s
copied min      : 32.0 MB/s
TXT;
    }

    private function getNetwork()
    {
        return <<<TXT
Download Cachefly: <b> 57.1MB/s </b>
Download Linode, Atlanta, GA, USA: <b> 4.98MB/s </b>
Download Linode, Dallas, TX, USA: <b> 7.83MB/s </b>
Download Linode, Tokyo, JP: <b> 7.05MB/s </b>
Download Linode, London, UK: <b> 46.1MB/s </b>
Download OVH, Paris, France: <b> 2.07MB/s </b>
Download SmartDC, Rotterdam, Netherlands: <b> 28.0MB/s </b>
Download Hetzner, Nuernberg, Germany: <b> 30.2MB/s </b>
Download iiNet, Perth, WA, Australia: <b> 1.43MB/s </b>
Download Leaseweb, Haarlem, NL: <b> 43.2MB/s </b>
Download Leaseweb, Manassas, VA, USA: <b> 9.61MB/s </b>
Download Softlayer, Singapore: <b> 2.79MB/s </b>
Download Softlayer, Seattle, WA, USA: <b> 7.78MB/s </b>
Download Softlayer, San Jose, CA, USA: <b> 7.82MB/s </b>
Download Softlayer, Washington, DC, USA: <b> 4.76MB/s </b>
TXT;
    }
}
