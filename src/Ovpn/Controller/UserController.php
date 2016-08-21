<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;


class UserController extends Controller
{
    use GetSecurityTrait;

    /**
     * @Route('/user/login')
     */
    public function loginAction()
    {
        $result = $this->getSecurityFacade()->doLogin(
            (string) $this->getRequest()->post('email'),
            (string) $this->getRequest()->post('password')
        );
        
        $this->setJsonResponse([
            'error' => ! $result,
            'message' => [\Kohana::message('user', 'accountNotFound')]
        ]);
    }

    /**
     * Email verification
     * @Route('/user/verification')
     */
    public function verificationAction()
    {
        
    }
}