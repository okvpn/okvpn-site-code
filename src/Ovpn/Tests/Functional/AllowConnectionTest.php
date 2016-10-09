<?php

namespace Ovpn\Tests\Functional;

use Ovpn\Entity\Roles;
use Ovpn\TestFramework\WebTestCase;

/**
 * @dbIsolation
 */
class AllowConnectionTest extends WebTestCase
{
    /**
     * @var 
     */
    protected static $traffic;
    
    protected static $balance;
    
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    /**
     * @dataProvider clientDataProvider
     * 
     * @param $balance
     * @param $traffic
     * @param Roles $role
     * @param $result
     */
    public function testClientAllowConnect($balance, $traffic, Roles $role, $result)
    {
        
    }
    
    public function clientDataProvider()
    {
        
    }
}