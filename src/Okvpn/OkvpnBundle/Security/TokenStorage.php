<?php

namespace Okvpn\OkvpnBundle\Security;

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
