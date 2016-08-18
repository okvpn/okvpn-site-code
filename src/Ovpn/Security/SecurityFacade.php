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
     * @var TokenStorageInterface[]
     */
    protected $tokenStrategy;

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
        $this->tokenStrategy = $tokens;
    }

    /**
     * @return Users | null
     */
    public function getAbstractUser()
    {
        $user = null;
        $restoreTokens = [];
        
        foreach ($this->tokenStrategy->getTokens() as $token) {
            $this->security->setTokenStorage($token);
            $user = $this->security->getAbstractUser();

            if ($user instanceof UsersInterface) {
                break;
            } else {
                $restoreTokens[] = $token;
            }
        }
        
        if ($user instanceof  UsersInterface) {
            /** @var TokenStorageInterface $token */
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