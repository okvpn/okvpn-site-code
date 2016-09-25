<?php

use PHPUnit\Framework\TestCase;
use Ovpn\Security\TokenCookie;
use Ovpn\Entity\UsersInterface;

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

        /** @var  Ovpn\Security\TokenSession $token */
        $token = (new \ReflectionClass('Ovpn\Security\TokenSession'))
            ->newInstanceWithoutConstructor();

        $token->setDriver($session);
        $token->setToken('111');
        $this->assertSame($token->getToken(), '111');
    }

    public function testSetCookieToken()
    {
        $user = $this->getUserProvider();

        $reflect = new \ReflectionClass('Ovpn\Security\TokenCookie');
        /** @var TokenCookie $storage */
        $storage = $reflect->newInstanceWithoutConstructor();

        $reflect->getProperty('abstractUser')
            ->setValue($storage, $user);

        $reflect->getProperty('cookieDriver')
            ->setValue($storage, new class {
                public function set() {}
            });

        $storage->setToken('10');
    }

    /**
     * @dataProvider getData
     */
    public function testGetCookieToken($user, $token, $result)
    {
        $reflect = new \ReflectionClass('Ovpn\Security\TokenCookie');
        /** @var TokenCookie $storage */
        $storage = $reflect->newInstanceWithoutConstructor();

        $reflect->getProperty('abstractUser')
            ->setValue($storage, $user);
        $reflect->getProperty('cookieDriver')
            ->setValue($storage, new class($token) {
                public $token;

                public function __construct($token)
                {
                    $this->token = $token;
                }

                public function get($id)
                {
                    return ($id) ? $this->token : null;
                }
            });

        $this->assertSame($storage->getToken(), $result);
    }

    public function getData()
    {
        $user = $this->getUserProvider();
        return [
            [$user, $this->getValidToken(), $user->getInstance('any')],
            [$user, $this->getBadToken(), null]
        ];
    }

    /**
     * @return UsersInterface
     */
    private function getUserProvider()
    {
        $user   = $this->createMock('Ovpn\Entity\UsersInterface');
        $user2  = $this->createMock('Ovpn\Entity\UsersInterface');

        $user2->expects($this->any())
            ->method('getToken')
            ->willReturn('1111');
        $user2->expects($this->any())
            ->method('getId')
            ->willReturn('1');
        $user->expects($this->any())
            ->method('getInstance')
            ->willReturn($user2);

        return $user;
    }

    private function getValidToken()
    {
        return 'eyJpZCI6IjEiLCJoYXNoIjoiMzMyNzVhOGFhNDhlYTkxOGJkNTNhOTE4MWFhOTc1ZjE1YWIwZDA2NDUzOThmNTkxOGEw' .
            'MDZkMDg2NzVjMWNiMjdkNWM2NDVkYmQwODRlZWU1NmU2NzVlMjViYTQwMTlmMmVjZWEzN2NhOWUyOTk1YjQ5ZmNiMTJjMDk2' .
            'YTAzMmUiLCJub25jZSI6MTQ3MTUyNDUxNn0=';
    }

    private function getBadToken()
    {
        return 'eyJpZCI6IjEiLCJoYXNoIjoiMzMyNzVhOGFhNDhlYTkxOGJkNTNhOTE4MWFhOTc1ZjE1YWIwZDA2NDUzOThmNTkxOGEw' .
            'MDZkMDg2NzVjMWNiMjdkNWM2NDVkYmQwODRlZWU1NmU2NzVlMjViYTQwMTlmMmVjZWEzN2NhOWUyOTk1YjQ5ZmNiMTJjMDk2' .
            'YTAzMmUiLCJub25jZSI6MTQ3MTUkNDUxNQ0=';
    }

}