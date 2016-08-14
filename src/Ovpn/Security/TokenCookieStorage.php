<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;

class TokenCookieStorage implements TokenStorageInterface
{
    protected $name = 'remember';
    
    /**
     * @inheritdoc
     */
    public function getToken()
    {
        $token = \Cookie::get('remember');
        
        //TODO:: shoud be fixed in 2.4
        if ($userInfo = base64_decode($token) and $userInfo = json_decode($userInfo, true)) {
            $user = new Users($userInfo['id']);
            return (hash('sha512', $user->getToken()) == $userInfo['id']) ? $user : null;
        }
        return null;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        //TODO:: shoud be fixed in 2.0
    }
}