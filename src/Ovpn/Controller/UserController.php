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
     * @Route('/user/logout')
     */
    public function logoutAction()
    {
    }

    /**
     * Email verification
     * @Route('/user/verify/{token}')
     */
    public function verifyAction()
    {
        $result = $this->getUserManager()->confirmEmail(
            $this->getRequest()->param('token')
        );
        
        if ($result instanceof  UsersInterface) {
            (new TokenSession())->setToken($result->getId());
            $this->redirect(\URL::base(true) . 'profile');
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
        $result = $um->createUser($this->getRequest()->post());
        
        $this->setJsonResponse($result);
    }

    /**
     * @Route('/user/update')
     */
    public function updateAction()
    {
        $this->responseView('');
    }

    /**
     * @Route('/user/newpasswordrequest')
     */
    public function newPasswordRequestAction()
    {
        $response = ! $this->getUserManager()
            ->setUserToken($this->getRequest()->post('email'));


        $this->setJsonResponse(
            [
                'error' => $response,
                'message' => $response ? \Kohana::message('user', 'loginErr') : ''
            ]
        );
    }

    /**
     * @Route('/user/resetpassword/{token}')
     */
    public function resetPasswordAction()
    {
        $token = $this->getRequest()->param('token');
        $user = $this->container->get('ovpn_user.repository')->findUserByToken($token);
        
        if ($user === null) {
            throw new NotFoundException();
        }
        
        $this->responseView('resetPassword', ['token' => $token]);
    }

    /**
     * @Route('/user/setnewpassword')
     */
    public function setNewPasswordAction()
    {
        $response = $this->prepareRequestForResetPassword();

        if (true === $response) {
            $post = $this->getRequest()->post();
            $error = $this->getUserManager()->resetPassword($post['token'], $post['password']);
            $response = [
                'error' => ! $error,
                'message' => ''
            ];
        }

        $this->setJsonResponse($response);
    }

    protected function prepareRequestForResetPassword()
    {
        $post = $this->getRequest()->post();

        $postValid = \Validation::factory($post);
        $postValid->rule('token', 'not_empty')
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('confirm', 'not_empty');

        if (!$postValid->check()) {
            return [
                'error'   => true,
                'message' => array_values($postValid->errors('')),
            ];
        }

        if ($post['confirm'] !== $post['password']) {
            return [
                'error'   => true,
                'message' => [\Kohana::message('user', 'passwordNotMatch')],
            ];
        }

        return true;
    }

    /**
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('ovpn_user.manager');
    }
}
