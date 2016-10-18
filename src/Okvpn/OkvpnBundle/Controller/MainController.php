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
        $this->responseView(
            'faq',
            [
                'auth' => $this->getSecurityFacade()->getUser() instanceof UsersInterface
            ]
        );
    }

    /**
     * @Route('/proxy')
     */
    public function proxyAction()
    {
        $this->responseView(
            'proxy',
            [
                'auth' => $this->getSecurityFacade()->getUser() instanceof UsersInterface
            ]
        );
    }

    /**
     * @Route('/guide')
     */
    public function guideAction()
    {
        $this->responseView(
            'userguide',
            [
                'auth' => $this->getSecurityFacade()->getUser() instanceof UsersInterface
            ]
        );
    }
}
