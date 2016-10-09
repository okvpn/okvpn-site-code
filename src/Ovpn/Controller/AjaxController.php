<?php

namespace Ovpn\Controller;

use Ovpn\Core\Config;
use Ovpn\Core\Controller;
use Ovpn\Entity\UsersInterface;
use Ovpn\Repository\UserRepository;
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
            'auth'      => ($this->getSecurityFacade()->getUser() instanceof UsersInterface),
            'signup'    => URL::base() . 'user/create',
            'sitekey'   => $this->getConfig()->get('captcha:sitekey'),
            'login'     => URL::base() . 'user/login',
            'profile'   => URL::base() . 'profile',
            'resetPass' => URL::base() . 'user/newpasswordrequest',
        ];

        $this->setJsonResponse($data);
    }

    /**
     * @Route('/checkconnection')
     */
    public function checkConnectionAction()
    {
        $cert = $this->getRequest()->post('cert');
        $user = $this->getUserRepository()->findUserByCertName($cert);
        $allow = false;

        if ($user instanceof  UsersInterface) {
            $allow = $this->getUserRepository()->isAllowConnection($user->getId(), $cert);
        }

        $this->setJsonResponse(['allow' => $allow]);
    }

    /**
     * @return Config
     */
    protected function getConfig()
    {
        return $this->container->get('ovpn_config');
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->container->get('ovpn_user.repository');
    }
}
