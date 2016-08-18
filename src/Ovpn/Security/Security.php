<?php

namespace Ovpn\Security;

use Ovpn\Entity\UsersInterface;

class Security implements SecurityInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var UsersInterface
     */
    protected $abstractUser;
    
    public function __construct(UsersInterface $abstractUser)
    {
        $this->abstractUser = $abstractUser;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return TokenStorageInterface
     * @throws \Exception
     */
    public function getTokenStorage()
    {
        if (! $this->tokenStorage) {
            throw new \Exception('The token storage must be initialized');
        }
        return $this->tokenStorage;
    }
    
    /**
     * @inheritdoc
     */
    public function getAbstractUser()
    {
        $abstractUser = $this->tokenStorage->getToken();
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