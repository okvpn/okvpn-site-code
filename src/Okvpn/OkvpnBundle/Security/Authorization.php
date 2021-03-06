<?php

namespace Okvpn\OkvpnBundle\Security;

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
        $user = $this->userProvider->findUserByEmail($login, true);
        if (!$user) {
            return false;
        }

        $login = password_verify($password, $user->getPassword());

        foreach ($this->tokenStorage as $token) {
            if ($login && $token instanceof TokenInterface) {
                $token->setToken((string) $user->getId());
            }
        }
        return $login;
    }

    /**
     * {@inheritdoc}
     */
    public function doLogout()
    {
        /** @var TokenInterface $token */
        foreach ($this->tokenStorage as $token) {
            $token->removeToken();
        }
    }
}
