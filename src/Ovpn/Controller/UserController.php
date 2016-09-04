<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Core\HTTPFoundation\NotFoundException;
use Ovpn\Entity\UsersInterface;
use Ovpn\Model\UserManager;
use Ovpn\Security\TokenSession;


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
     * @Route('/user/verify')
     */
    public function verifyAction()
    {
        $result = $this->getUserManager()->confirmEmail(
            $this->getRequest()->param('token'));
        
        if ($result instanceof  UsersInterface) {
            (new TokenSession())->setToken($result->getId());
            $this->redirect( \URL::base(true) . 'profile');
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * @Route('/user/create')
     */
    public function createAction()
    {
        $um = $this->getUserManager();
        $result = $um->createUser($_POST);
        
        $this->setJsonResponse($result);
    }

    /**
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('ovpn_user.manager');
    }
}