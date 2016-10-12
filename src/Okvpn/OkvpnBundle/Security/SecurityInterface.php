<?php

namespace Okvpn\OkvpnBundle\Security;

use Okvpn\OkvpnBundle\Entity\Users;

/**
 * Interface SecurityInterface
 * PHP >= 7.0
 */
interface SecurityInterface
{
    /**
     * @return Users | null
     */
    public function getAbstractUser();

    /**
     * @param string $nameRole
     * @return bool
     */
    public function isGranted(string $nameRole): bool;
}
