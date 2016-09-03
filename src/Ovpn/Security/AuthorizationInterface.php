<?php

namespace Ovpn\Security;

interface AuthorizationInterface
{
    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function doLogin(string $login, string $password):bool;
}
