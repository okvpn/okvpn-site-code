<?php

namespace Ovpn\Controller;

use Ovpn\Core\Config;
use Ovpn\Core\Controller;
use Ovpn\Entity\UsersInterface;
use URL;

class AjaxController extends Controller
{
    use GetSecurityTrait;

    /**
     * @Route('/api')
     */
    public function apiAction()
    {
        $data = [
            'auth'    => ($this->getSecurityFacade()->getUser() instanceof UsersInterface),
            'signup'  => URL::base() . 'user/create',
            'sitekey' => $this->getConfig()->get('captcha:sitekey'),
            'login'   => URL::base() . 'user/login',
            'profile' => URL::base() . 'profile',
        ];

        $this->setJsonResponse($data);
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->container->get('ovpn_config');
    }

}
