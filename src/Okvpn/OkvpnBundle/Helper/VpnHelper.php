<?php

namespace Okvpn\OkvpnBundle\Helper;

use Okvpn\OkvpnBundle\Entity\VpnUser;
use Okvpn\OkvpnBundle\Security\SecurityFacade;

class VpnHelper
{
    /** @var  SecurityFacade */
    protected $securityFacade;
    
    public function __construct(SecurityFacade $securityFacade)
    {
        $this->securityFacade = $securityFacade;   
    }
    
    public function deleteIsApplicable(VpnUser $vpnItem)
    {
        $user = $this->securityFacade->getUser();
        return $user->getId() == $vpnItem->getUser()->getId();
    }
}
