<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;
use Okvpn\OkvpnBundle\Entity\UsersInterface;

class MainController extends Controller
{

    use GetSecurityTrait;

    /**
     * @Route('/faq')
     */
    public function faqAction()
    {
        $this->getResponse()->body(
            \View::factory('faq')
                ->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersInterface)
        );
    }

    /**
     * @Route('/proxy')
     */
    public function proxyAction()
    {
        $this->getResponse()->body(
            \View::factory('proxy')->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersInterface)
        );
    }

    /**
     * @Route('/guide')
     */
    public function guideAction()
    {
        $this->getResponse()->body(
            \View::factory('userguide')
                ->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersInterface)
        );
    }
}
