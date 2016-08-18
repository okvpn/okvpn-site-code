<?php

/**
 * Created by PhpStorm.
 * User: jurasikt
 * Date: 18.8.16
 * Time: 16.52
 */
class SecurityFacadeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ReflectionClass
     */
    public $reflect;

    public function setUp()
    {
        $this->reflect = new \ReflectionClass('Ovpn\Security\SecurityFacade');
        $this->reflect->getProperty('security')->setAccessible(true);
        $this->reflect->getProperty('authorization')->setAccessible(true);
        $this->reflect->getProperty('tokenStrategy')->setAccessible(true);
    }

    public function testGetUser()
    {
        $facade = $this->reflect->newInstanceWithoutConstructor();
        $this->reflect;
    }
}
