<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;

class TokenCookieStorage implements TokenStorageInterface
{
    protected $name = 'rememberme';
    
    /**
     * @inheritdoc
     */
    public function getToken()
    {
        $token = \Cookie::get($this->name);
        
        //TODO:: shoud be fixed in 2.4
        if ($userInfo = base64_decode($token) and $userInfo = json_decode($userInfo, true)) {
            $user = new Users($userInfo['id']);
            return (hash('sha512', $user->getToken()) == $userInfo['hash']) ? $user : null;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setToken(string $token)
    {
        //TODO:: shoud be fixed in 2.4
        $user = new Users($token);
        $token = hash('sha512', $user->getToken());
        \Cookie::set($this->name, $this->encodeToken([
            'id' => $user->getId(),
            'hash' => $token,
            'nonce' => time(),
        ]));
    }

    private function encodeToken(array $data)
    {
        return base64_encode(json_encode($data));
    }
}