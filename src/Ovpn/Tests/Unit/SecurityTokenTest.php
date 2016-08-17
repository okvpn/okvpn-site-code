<?php

use PHPUnit\Framework\TestCase;

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
}