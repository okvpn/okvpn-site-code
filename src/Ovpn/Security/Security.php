<?php

namespace Ovpn\Security;

use Ovpn\Entity\UsersInterface;

class Security implements SecurityInterface
{
    /**
     * @var TokenInterface
     */
    protected $tokenStrategy;

    /**
     * @var UsersInterface
     */
    protected $abstractUser;
    
    public function __construct(UsersInterface $abstractUser)
    {
        $this->abstractUser = $abstractUser;
    }

    /**
     * @param TokenInterface $tokenStrategy
     */
    public function setTokenStrategy(TokenInterface $tokenStrategy)
    {
        $this->tokenStrategy = $tokenStrategy;
    }

    /**
     * @return TokenInterface
     * @throws \Exception
     */
    public function getTokenStrategy()
    {
        if (! $this->tokenStrategy) {
            throw new \Exception('The token storage must be initialized');
        }
        return $this->tokenStrategy;
    }
    
    /**
     * @inheritdoc
     */
    public function getAbstractUser()
    {
        $abstractUser = $this->tokenStrategy->getToken();
        return $abstractUser ? $this->abstractUser->getInstance($abstractUser) : null;
    }

    /**
     * @inheritdoc
     */
    public function isGranted(string $roleName): bool
    {
        return in_array($this->getAbstractUser()->getRole()->getRolesName(), $roleName);
    }
}
