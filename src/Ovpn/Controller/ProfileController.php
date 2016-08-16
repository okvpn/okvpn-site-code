<?php

namespace Ovpn\Controller;


use Ovpn\Core\Controller;
use Ovpn\Entity\UsersIntrface;
use Ovpn\Repository\UserRepository;
use Ovpn\Repository\VpnRepository;
use Ovpn\Security\SecurityFacade;

class ProfileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function before()
    {
        if (! ($this->getSecurity()->getUser() instanceof UsersIntrface)) {
            throw new \HTTP_Exception_401();
        }
    }

    /**
     * @Route('/profile')
     */
    public function indexAction()
    {
        $this->getResponse()->body(
            \View::factory('profile')->set('csrf', '123')
        );
    }

    /**
     * @Route('/profile/create')
     */
    public function createAction()
    {
        $response = \View::factory('create-vpn')
            ->set('vpn', (new VpnRepository())->getVpnStatus())
            ->set('csrf', '123');

        $this->getResponse()->body($response);
    }

    /**
     * @Route('/profile/getinfovpn')
     */
    public function getInfoVpnAction()
    {
        $token = $this->getRequest()->param('token');
        $info = (new VpnRepository())->getVpnInformation($token);

        $response = \View::factory('ajax/vpninfo')
            ->set('id', $token)
            ->set('network', preg_replace('/\n/', "<br>", $info[0]['network']))
            ->set('link', $info[0]['specifications_link'])
            ->set('csrf', '123');

        $this->getResponse()->body($response);
    }

    /**
     * @Route('/profile/billing')
     */
    public function billingAction()
    {
        $user = $this->getSecurity()->getUser();

        $this->setJsonResponse(
            (new UserRepository())->getTrafficMeters($user->getId())
        );
    }

    /**
     * @return SecurityFacade
     */
    private function getSecurity()
    {
        return $this->container->get('ovpn_security');
    }

}