<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Entity\UsersIntrface;

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
                ->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersIntrface)
        );
    }

    /**
     * @Route('/proxy')
     */
    public function proxyAction()
    {
        $this->getResponse()->body(\View::factory('proxy')
            ->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersIntrface)
            );
    }

    /**
     * @Route('/guide')
     */
    public function guideAction()
    {
        $this->getResponse()->body(\View::factory('userguide')
                ->set('auth', $this->getSecurityFacade()->getUser() instanceof UsersIntrface)
        );
    }

}
