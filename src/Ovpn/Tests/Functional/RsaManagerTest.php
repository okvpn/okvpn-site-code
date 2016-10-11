<?php

namespace Ovpn\Tests\Functional;

use Ovpn\TestFramework\WebTestCase;
use Ovpn\Tools\Openvpn\OpenvpnFacade;

class RsaManagerTest extends WebTestCase
{
    protected static $name;

    /**
     * @var OpenvpnFacade
     */
    protected $rsaManager;

    public function setUp()
    {
        $this->rsaManager = $this->getClient()->getContainer()->get('ovpn_openvpn.facade');
    }
    
    public function testGen()
    {
        self::$name = uniqid('test');
        
        $this->rsaManager->setClientName(self::$name, 'pa1');
        $config = $this->rsaManager->buildCommonUpdConfig('test.com');

        $this->assertGreaterThanOrEqual(7000, strlen($config));
        $this->assertContains(self::$name, $config);
    }
}
