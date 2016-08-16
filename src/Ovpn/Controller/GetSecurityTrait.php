<?php

namespace Ovpn\Controller;

use Ovpn\Security\SecurityFacade;

trait GetSecurityTrait
{
    /**
     * @return SecurityFacade
     */
    protected function getSecurityFacede()
    {
        return $this->getContainer()->get('ovpn_security');
    }
}