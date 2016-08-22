<?php

use PHPUnit\Framework\TestCase;
use Ovpn\Security\SecurityFacade;
use Ovpn\Security\TokenStorage;

class SecurityFacadeTest extends TestCase
{

    protected $token;

    protected $abstractUser;

    protected $tokenStorage;

    protected $security;

    protected $authorization;

    public function setUp()
    {
        $this->token = $this->createMock('Ovpn\Security\TokenInterface');
        $this->abstractUser = $this->createMock('Ovpn\Entity\UsersInterface');
        $this->authorization = $this->createMock('Ovpn\Security\Authorization');
        $this->security = $this->createMock('Ovpn\Security\Security');
        $this->tokenStorage = new TokenStorage();
        $this->tokenStorage->addToken($this->token);
    }

    public function testGetUser()
    {
        $this->security->expects($this->once())
            ->method('setTokenStrategy');
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
