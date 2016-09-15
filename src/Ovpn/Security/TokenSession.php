<?php

namespace Ovpn\Security;

class TokenSession implements TokenInterface
{

    protected $name = 'user_id';

    /**
     * @var \Session
     */
    protected $sessionDriver;

    public function __construct()
    {
        $this->sessionDriver = \Session::instance();
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->sessionDriver->get($this->name);
    }

    /**
     * @inheritdoc
     */
    public function setToken(string $token)
    {
        $this->sessionDriver->set($this->name, $token);
    }

    public function setDriver($driver)
    {
        $this->sessionDriver = $driver;
    }
}
