<?php

namespace Okvpn\OkvpnBundle\Tests\Unit;

use Okvpn\OkvpnBundle\Security\Authorization;
use PHPUnit\Framework\TestCase;

class AuthorizationTest extends TestCase
{
    protected $token;

    public function setUp()
    {
        $this->token = $this->createMock('Okvpn\OkvpnBundle\Security\TokenInterface');
    }

    /**
     * @dataProvider getPasswordData
     */
    public function testDoLogin($hashPassword, $realPassword, $result)
    {
        $abstractUser = $this->createMock('Okvpn\OkvpnBundle\Entity\UsersInterface');
        $userProvider = $this->createMock('Okvpn\OkvpnBundle\Security\UserProviderInterface');
        $tokenStorage = $this->createMock('Okvpn\OkvpnBundle\Security\TokenStorage');

        $abstractUser->expects($this->once())
            ->method('getPassword')
            ->willReturn($hashPassword);
        $userProvider->expects($this->once())
            ->method('findUserByEmail')
            ->willReturn($abstractUser);

        $auth = new Authorization($tokenStorage, $userProvider);

        $this->assertSame($result, $auth->doLogin('login', $realPassword));
    }

    public function testDoLoginWhenUserNotFound()
    {
        $userProvider = $this->createMock('Okvpn\OkvpnBundle\Security\UserProviderInterface');
        $tokenStorage = $this->createMock('Okvpn\OkvpnBundle\Security\TokenStorage');
        $userProvider->expects($this->once())
            ->method('findUserByEmail')
            ->willReturn(null);

        $auth = new Authorization($tokenStorage, $userProvider);
        $this->assertSame($auth->doLogin('login', '123'), false);
    }

    /**
     * @expectedException \Throwable
     */
    public function testInvalidTypeArgument()
    {
        $userProvider = $this->createMock('Okvpn\OkvpnBundle\Security\UserProviderInterface');
        $tokenStorage = $this->createMock('Okvpn\OkvpnBundle\Security\TokenStorage');

        (new Authorization($tokenStorage, $userProvider))->doLogin(null, '123456');
    }

    public function getPasswordData()
    {
        return [
            ['bad hash pass', '123456', false],
            [$this->getHashPassword('123456'), '123456', true]
        ];
    }

    protected function getHashPassword($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }
}
