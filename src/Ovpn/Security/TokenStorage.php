<?php

namespace Ovpn\Security;

class TokenStorage extends \SplObjectStorage
{

    protected $tokens = [];

    /**
     * @param TokenInterface $token
     * @param int $priority
     */
    public function addToken(TokenInterface $token, $priority)
    {
        if (array_key_exists($priority, $this->tokens)) {
            throw new \InvalidArgumentException('priority must be unequal for tags secure.token');
        }

        $this->tokens[$priority] = $token;
    }

    /**
     * Sort tokens by priority key
     */
    public function compile()
    {
        ksort($this->tokens);
        foreach ($this->tokens as $token) {
            $this->attach($token);
        }
    }
}