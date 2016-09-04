<?php

namespace Ovpn\Security;

class TokenStorage extends \SplObjectStorage
{

    /**
     * @param TokenInterface $token
     */
    public function addToken(TokenInterface $token)
    {
        $this->attach($token);
    }
}
