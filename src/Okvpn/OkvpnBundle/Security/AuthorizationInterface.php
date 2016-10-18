<?php

namespace Okvpn\OkvpnBundle\Security;

interface AuthorizationInterface
{
    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function doLogin(string $login, string $password):bool;

    /**
     * Remove all cookie and session token
     */
    public function doLogout();
}
