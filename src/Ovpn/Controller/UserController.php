<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Security\SecurityFacade;

class UserController extends Controller
{
    use GetSecurityTrait;

    /**
     * @Route('/user/login')
     */
    public function loginAction()
    {
        $result = $this->getSecurityFacede()->doLogin(
            (string) $this->getRequest()->post('email'),
            (string) $this->getRequest()->post('password')
        );
        
        $this->setJsonResponse([
            'error' => ! $result,
            'message' => [\Kohana::message('user', 'accountNotFound')]
        ]);
    }
}