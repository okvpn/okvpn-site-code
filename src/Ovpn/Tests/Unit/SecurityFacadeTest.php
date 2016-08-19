<?php

use PHPUnit\Framework\TestCase;
use Ovpn\Security\SecurityFacade;

class SecurityFacadeTest extends TestCase
{

    protected $token;

    protected $abstractUser;

    protected $tokenStorage;

    protected $security;

    protected $authorization;

    public function setUp()
    {
        $this->token = $this->createMock('Ovpn\Security\TokenStorageInterface');
        $this->abstractUser = $this->createMock('Ovpn\Entity\UsersInterface');
        $this->authorization = $this->createMock('Ovpn\Security\Authorization');
        $this->security = $this->createMock('Ovpn\Security\Security');
        $this->tokenStorage = $this->createMock('Ovpn\Security\TokenStorage');
    }

    public function testGetUser()
    {
        $this->tokenStorage->expects($this->once())
            ->method('getTokens')
            ->willReturn(array_fill(0, 3, $this->token));
        $this->security->expects($this->once())
            ->method('setTokenStorage');
        $this->security->expects($this->once())
            ->method('getAbstractUser')
            ->willReturn($this->abstractUser);

        $facade = new SecurityFacade($this->authorization, $this->security, $this->tokenStorage);
        $this->assertSame($facade->getUser(), $this->abstractUser);
    }

    public function testDoLogin()
    {
        $this->authorization->expects($this->once())
            ->method('doLogin');
        $facade = new SecurityFacade($this->authorization, $this->security, $this->tokenStorage);
        $facade->doLogin('email','password');
    }
}
