<?php

namespace Ovpn\Security;


class TokenSessionStorage implements TokenStorageInterface
{
    protected  $name = 'user_id';

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return \Session::instance()->get($this->name);
    }

    /**
     * @inheritdoc
     */
    public function setToken(string $token)
    {
        \Session::instance()->set($this->name, $token);
    }
}