<?php

namespace Ovpn\Security;

class TokenStorage extends \SplObjectStorage
{

    public function addToken(TokenInterface $token)
    {
        $this->attach($token);
    }

    /**
     * todo: use spl iterator
     */
    public function getToken(){}
}