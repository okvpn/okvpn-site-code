<?php

use PHPUnit\Framework\TestCase;
use Ovpn\Security\TokenCookieStorage;

class SecurityTokenTest extends TestCase
{

    public function testSessionToken()
    {
        $session = $this->createMock('\Session');

        $session->expects($this->once())
            ->method('get')
            ->willReturn('111');

        $session->expects($this->once())
            ->method('set');

        /** @var  Ovpn\Security\TokenSessionStorage $token */
        $token = (new \ReflectionClass('Ovpn\Security\TokenSessionStorage'))
            ->newInstanceWithoutConstructor();

        $token->setDriver($session);
        $token->setToken('111');
        $this->assertSame($token->getToken(), '111');
    }

    public function testSetCookieToken()
    {
        $cookie = $this->createMock('\Cookie');
        $user   = $this->createMock('Ovpn\Entity\UsersInterface');
        $user2  = $this->createMock('Ovpn\Entity\UsersInterface');

        $user2->expects($this->any())
            ->method('getToken');
        $user2->expects($this->any())
            ->method('getId');

        $user->expects($this->any())
            ->method('getInstance')
            ->willReturn($user2);

        $cookie->expects($this->any())
            ->method('set');

        $reflect = new \ReflectionClass('Ovpn\Security\TokenCookieStorage');
        /** @var TokenCookieStorage $storage */
        $storage = $reflect->newInstanceWithoutConstructor();

        $reflect->getProperty('abstractUser')
            ->setValue($storage, $user);
        $reflect->getProperty('cookieDriver')
            ->setValue($storage, $cookie);

        $storage->setToken('10');
    }
}