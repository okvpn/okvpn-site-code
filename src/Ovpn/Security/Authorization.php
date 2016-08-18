<?php

namespace Ovpn\Security;

use Ovpn\Model\UserProviderInterface;

class Authorization implements AuthorizationInterface
{

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    public function __construct(TokenStorage $storage, UserProviderInterface $userProvider)
    {
        $this->tokenStorage = $storage;
        $this->userProvider = $userProvider;
    }

    /**
     * @inheritdoc
     */
    public function doLogin(string $login, string $password):bool
    {
        $user = $this->userProvider->findUserByEmail($login);
        if (!$user) {
            return false;
        }

        $login = password_verify($password, $user->getPassword());

        foreach ($this->tokenStorage->getTokens() as $token) {
            if ($login && $token instanceof TokenStorageInterface) {
                $token->setToken((string) $user->getId());
            }
        }
        return $login;
    }
    
}