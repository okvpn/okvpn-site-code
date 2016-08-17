<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Entity\UsersIntrface;
use URL;
use Kohana;

class AjaxController extends Controller
{
    use GetSecurityTrait;

    /**
     * @Route('/api')
     */
    public function apiAction()
    {
        $data = [
            'auth'    => ($this->getSecurityFacade()->getUser() instanceof UsersIntrface),
            'signup'  => URL::base() . 'signup',
            'sitekey' => Kohana::$config->load('info')->server->captcha->sitekey,
            'login'   => URL::base() . 'user/login',
            'profile' => URL::base() . 'profile',
        ];

        $this->setJsonResponse($data);
    }

}
