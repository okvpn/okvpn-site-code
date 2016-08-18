<?php

namespace Ovpn\Security;

class TokenStorage
{
    /**
     * @var TokenStorage[]
     */
    public $tokens;

    public function __construct(array $tokens = null)
    {
        if (null === $tokens) {
            $this->tokens = $tokens;
        } else {

            $this->tokens = [
                new TokenSessionStorage(),
                new TokenCookieStorage()
            ];
        }
    }

    /**
     * @param array $tokens
     */
    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * todo: use spl iterator
     */
    public function getToken(){}

    /**
     * @return TokenStorage[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}