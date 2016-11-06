<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\OkvpnBundle\Entity\Host;
use Okvpn\OkvpnBundle\Entity\Balance;
use Okvpn\OkvpnBundle\Entity\Roles;
use Okvpn\OkvpnBundle\Entity\Traffic;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Entity\VpnUser;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;

/**
 * @dbIsolation
 */
class AllowConnectionTest extends WebTestCase
{
    /**
     * @var Traffic
     */
    protected static $traffic;

    /**
     * @var Balance
     */
    protected static $balance;

    /**
     * @var Users
     */
    protected static $user;

    /**
     * @var VpnUser
     */
    protected static $vpnUser;
    
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        $user = new Users();
        $user->setRole(new Roles('free'))
            ->setEmail('test')
            ->setPassword('test')
            ->setChecked(true)
            ->setDate();
        $user->save();

        self::$vpnUser = new VpnUser();

        self::$vpnUser
            ->setUser($user)
            ->setActive(true)
            ->setCallback('1')
            ->setDateCreate()
            ->setDateDelete()
            ->setHost(self::getHost())
            ->setName('test')
            ->save();
        
        $balance = new Balance();
        $balance->setAmount(1.00)
            ->setType('free')
            ->setDate()
            ->setUser($user)
            ->save();
        
        $traffic = new Traffic();
        $traffic->setUser($user)
            ->setDate()
            ->setCount(1)
            ->save();
        
        self::$balance = $balance;
        self::$traffic = $traffic;
        self::$user    = $user;
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
        $this->getUser()->setRole($role)->save();
        $this->getBalance()->setAmount($balance)->save();
        $this->getTraffic()->setCount($traffic)->save();
        
        $this->request('POST', '/ajax/checkconnection', ['cert' => 'test']);
        $response = $this->getJsonResponse();
        $this->assertSame($response['allow'], $result);
    }

    /**
     * @depends testClientAllowConnect
     */
    public function testClientAllowConnectWhenCertNotActive()
    {
        self::$vpnUser->setActive(false)->save();
        $this->request('POST', '/ajax/checkconnection', ['cert' => 'test']);
        $response = $this->getJsonResponse();
        $this->assertSame($response['allow'], false);
    }

    /**
     * @depends testClientAllowConnect
     */
    public function testClientAllowConnectWhenUserNotActive()
    {
        self::$vpnUser->setActive(true)->save();

        $this->getUser()->setChecked(false)->save();
        $this->request('POST', '/ajax/checkconnection', ['cert' => 'test']);
        $response = $this->getJsonResponse();
        $this->assertSame($response['allow'], false);
    }
    
    public function clientDataProvider()
    {
        return [
            [
                'balance' => 0.0,
                'traffic' => 0.0,
                'role'    => new Roles('free'),
                'result'  => true
            ],
            [
                'balance' => 0.0,
                'traffic' => 16900.0,
                'role'    => new Roles('free'),
                'result'  => false
            ],
            [
                'balance' => 0.0,
                'traffic' => 16900.0,
                'role'    => new Roles('full'),
                'result'  => false
            ],
            [
                'balance' => 0.01,
                'traffic' => 16900.0,
                'role'    => new Roles('admin'),
                'result'  => true
            ],
            [
                'balance' => 0.10,
                'traffic' => 169000.0,
                'role'    => new Roles('full'),
                'result'  => false
            ],
            [
                'balance' => 0.10,
                'traffic' => 16900.0,
                'role'    => new Roles('full'),
                'result'  => true
            ],
        ];
    }

    /**
     * @return Users
     */
    protected function getUser()
    {
        return self::$user;
    }

    /**
     * @return Traffic
     */
    protected function getTraffic()
    {
        return self::$traffic;
    }
    
    /**
     * @return Balance
     */
    protected function getBalance()
    {
        return self::$balance;
    }

    /**
     * @return Host
     */
    protected static function getHost()
    {
        $vpnServer = new Host();
        $vpnServer->where('name', '=', 'pa1')->find();
        return $vpnServer;
    }
}
