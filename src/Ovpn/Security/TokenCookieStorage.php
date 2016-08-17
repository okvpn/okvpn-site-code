<?php

namespace Ovpn\Security;

use Ovpn\Entity\Users;
use Ovpn\Entity\UsersInterface;

class TokenCookieStorage implements TokenStorageInterface
{
    protected $name = 'rememberme';

    /**
     * @var UsersInterface
     */
    protected $abstractUser;

    /**
     * @var /Cookie
     */
    protected $cookieDriver;


    public function __construct()
    {
        $this->cookieDriver = new \Cookie();
        $this->abstractUser = new Users();
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        $token = $this->cookieDriver->get($this->name);

        //TODO:: shoud be fixed in 2.1
        if ($userInfo = base64_decode($token) and $userInfo = json_decode($userInfo, true)) {
            $user = $this->abstractUser->getInstance($userInfo['id']);
            return (hash('sha512', $user->getToken()) == $userInfo['hash']) ? $user : null;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function setToken(string $token)
    {
        //TODO:: shoud be fixed in 2.1
        $user = $this->abstractUser->getInstance($token);
        $token = hash('sha512', $user->getToken());

        $encodeToken = $this->encodeToken([
            'id' => $user->getId(),
            'hash' => $token,
            'nonce' => time(),
        ]);

        $this->cookieDriver->set($this->name, $encodeToken);
    }

    private function encodeToken(array $data)
    {
        return base64_encode(json_encode($data));
    }
}