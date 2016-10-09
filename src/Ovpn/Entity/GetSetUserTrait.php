<?php

namespace Ovpn\Entity;

trait GetSetUserTrait
{
    /**
     * @param Users $user
     * @return $this
     */
    public function setUser(Users $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->user;
    }
}