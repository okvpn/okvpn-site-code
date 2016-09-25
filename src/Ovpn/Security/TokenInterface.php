<?php

namespace Ovpn\Security;

interface TokenInterface
{
    /**
     * Return Auth token
     *
     * @return string | null
     */
    public function getToken();

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token);
}
