<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;
use Ovpn\Entity\UsersInterface;

class SecurityFacade implements SecurityInterface
{

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var TokenStorageInterface[]
     */
    protected $tokenStrategy;

    /**
     * @var Authorization;
     */
    protected $authorization;
    
    public function __construct(array $tokens = null)
    {
        //todo: must be refactoring
        $this->security = new Security();

        if (null === $tokens) {
            $this->tokenStrategy = [
                new TokenSessionStorage(),
                new TokenCookieStorage(),
            ];

        } else {
            $this->tokenStrategy = $tokens;
        }
        //todo: must be reefactoring
        $this->authorization = new Authorization($tokens);
    }

    /**
     * @return Users | null
     */
    public function getUser()
    {
        $user = null;
        foreach ($this->tokenStrategy as $token) {
            $this->security->setTokenStorage($token);
            $user = $this->security->getUser();

            if ($user instanceof UsersInterface) {
                break;
            }
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function isGranted(string $nameRole): bool
    {
        $result = false;
        foreach ($this->tokenStrategy as $token) {
            $this->security->setTokenStorage($token);
            $result = $this->security->isGranted($nameRole);

            if ($result) {
                break;
            }
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