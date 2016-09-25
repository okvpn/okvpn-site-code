<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;
use Ovpn\Entity\UsersInterface;

class SecurityFacade implements SecurityInterface, AuthorizationInterface
{

    /**
     * @var Security
     */
    protected $security;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var Authorization;
     */
    protected $authorization;
    
    public function __construct(
        AuthorizationInterface $authorization,
        SecurityInterface $security,
        TokenStorage $tokens
    ) {

        $this->security = $security;
        $this->authorization = $authorization;
        $this->tokenStorage = $tokens;
    }

    /**
     * @return Users | null
     */
    public function getAbstractUser()
    {
        $user = null;
        $restoreTokens = [];
        /** @var TokenInterface $token */
        foreach ($this->tokenStorage as $token) {
            $this->security->setTokenStrategy($token);
            $user = $this->security->getAbstractUser();

            if ($user instanceof UsersInterface) {
                break;
            } else {
                $restoreTokens[] = $token;
            }
        }
        
        if ($user instanceof  UsersInterface) {
            /** @var TokenInterface $token */
            foreach ($restoreTokens as $token) {
                $token->setToken($user->getId());
            }
        }
        
        return $user;
    }

    /**
     * @return null|Users
     */
    public function getUser()
    {
        return $this->getAbstractUser();
    }

    /**
     * @inheritdoc
     */
    public function isGranted(string $nameRole): bool
    {
        $result = false;
        /** @var TokenInterface $token */
        foreach ($this->tokenStorage as $token) {
            $this->security->setTokenStrategy($token);
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
