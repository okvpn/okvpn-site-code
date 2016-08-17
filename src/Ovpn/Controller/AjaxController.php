<?php

namespace Ovpn\Controller;

use Annotations\DependencyInjectionAnnotation as DI;
use Ovpn\Core\Controller;
use Ovpn\Entity\UsersIntrface;
use Ovpn\Security\SecurityFacade;
use URL;
use Kohana;
use View;

class AjaxController extends Controller
{
    use GetSecurityTrait;

    /**
     * @Route('/api')
     */
    public function apiAction()
    {

        $data = [
            'auth'    => ($this->getSecurityFacede()->getUser() instanceof UsersIntrface),
            'signup'  => URL::base() . 'signup',
            'sitekey' => Kohana::$config->load('info')->server->captcha->sitekey,
            'login'   => URL::base() . 'user/login',
            'profile' => URL::base() . 'profile',
        ];

        $this->setJsonResponse($data);
    }

    public function action_billing()
    {
        $user = $this->getUser();

        if ($user === null) {
            throw new \HTTP_Exception_401();
        }

        $this->response->headers('Content-type', 'application/json')
            ->body(json_encode($this->getUserManager()->getTrafficMeters($user)));
    }

    public function action_getinfovpn()
    {
        
        $info = Model::factory('Server')
            ->getVpnInfo($this->request->param('token'));

        if (empty($info)) {
            throw new \HTTP_Exception_404();
        }

        $this->response->body(View::factory('ajax/vpninfo')
                ->set('network', preg_replace('/\n/', "<br>", $info[0]['network']))
                ->set('link', $info[0]['specifications_link'])
                ->set('csrf', $this->getUserManager()->setCsrfToken(false))
                ->set('id', $this->request->param('token'))
            );
    }

    protected function getUser()
    {
        if ($this->_user !== null) {
            return $this->_user;
        }
        return null;
        /*$this->_user = (new Model_UserManager)->secureContext()->getUser();
        return $this->_user;*/
    }

    protected function getUserManager()
    {
        if ($this->_userManager !== null) {
            return $this->_userManager;
        }
        $this->_userManager = new Model_UserManager(); 
        return $this->_userManager;
    }

}
