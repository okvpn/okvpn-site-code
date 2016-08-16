<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;
use Ovpn\Entity\UsersIntrface;

class SecurityFacade implements SecurityInterface
{

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var Authorization;
     */
    protected $authorization;
    
    public function __construct()
    {
        $this->security = new Security();
        $this->authorization = new Authorization([
            new TokenCookieStorage(),
            new TokenSessionStorage()
        ]);
    }

    /**
     * @return Users | null
     */
    public function getUser()
    {
        $this->security->setTokenStorage(new TokenSessionStorage());
        $user = $this->security->getUser();

        if (!$user) {
            $this->security->setTokenStorage(new TokenCookieStorage());
            $user = $this->security->getUser();

            if ($user instanceof  UsersIntrface) {
                (new TokenSessionStorage())->setToken($user->getId());
            }
        }
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function isGranted(string $nameRole): bool
    {
        $this->security->setTokenStorage(new TokenSessionStorage());
        $result = $this->security->isGranted($nameRole);
        
        if (!$result) {
            $this->security->setTokenStorage(new TokenCookieStorage());
            $result = $this->security->isGranted($nameRole);
        }
        return $result;
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function doLogin(string $login, string $password): bool
    {
        return $this->authorization->doLogin($login, $password);
    }
}