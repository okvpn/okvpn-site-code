<?php

namespace Okvpn\OkvpnBundle\Security;

use Okvpn\OkvpnBundle\Entity\UsersInterface;

interface UserProviderInterface
{
    /**
     * @param $email
     * @param bool $onlyActive
     * @return UsersInterface
     */
    public function findUserByEmail($email, $onlyActive = false);
}
