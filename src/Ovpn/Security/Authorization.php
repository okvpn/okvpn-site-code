<?php

namespace Ovpn\Security;

use Ovpn\Repository\UserRepository;

class Authorization
{

    /**
     * @var TokenStorageInterface[]
     */
    protected $tokenStorages;

    public function __construct(array $storage)
    {
        $this->tokenStorages = $storage;
    }

    public function doLogin(string $login, string $password):bool
    {
        $user = (new UserRepository())->findUserByEmail($login);
        if (!$user) {
            return false;
        }

        $login = password_verify($password, $user->getPassword());

        foreach ($this->tokenStorages as $tokenStorage) {
            if ($login && $tokenStorage instanceof TokenStorageInterface) {
                $tokenStorage->setToken((string) $user->getId());
            }
        }
        return $login;
    }
    
}