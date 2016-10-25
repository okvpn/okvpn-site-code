<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\Core\Annotations\Inject as DI;
use Okvpn\OkvpnBundle\Security\SecurityFacade;

trait GetSecurityTrait
{
    /**
     * @var SecurityFacade
     * @DI(service="ovpn_security")
     */
    protected $securityFacade;

    /**
     * @return SecurityFacade
     * @throws \RuntimeException
     */
    public function getSecurityFacade()
    {
        if (! $this->securityFacade instanceof SecurityFacade) {
            throw new \RuntimeException('"securityFacade" must be instance of SecurityFacade and 
                must be inject from DI');
        }

        return $this->securityFacade;
    }

    public function setSecurityFacade(SecurityFacade $facade)
    {
        $this->securityFacade = $facade;
    }
}
