<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;

class Security implements SecurityInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

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
    public function getUser()
    {
        //todo:: must be refactired
        $uid = $this->tokenStorage->getToken();
        return $uid ? new Users($uid) : null;
    }

    /**
     * @inheritdoc
     */
    public function isGranted(string $roleName): bool
    {
        return in_array($this->getUser()->getRole()->getRolesName(), $roleName);
    }
    
}