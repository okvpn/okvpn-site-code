<?php

namespace Ovpn\Security;

use Ovpn\Entity\UsersInterface;

interface UserProviderInterface
{
    /**
     * @param $email
     * @param bool $onlyActive
     * @return UsersInterface
     */
    public function findUserByEmail($email, $onlyActive = false);
}
