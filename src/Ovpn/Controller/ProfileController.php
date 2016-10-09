<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Core\HTTPFoundation\AccessDeniedException;
use Ovpn\Entity\UsersInterface;
use Ovpn\Repository\UserRepository;
use Ovpn\Repository\VpnRepository;

class ProfileController extends Controller
{
    use GetSecurityTrait;
    
    /**
     * @inheritdoc
     */
    public function before()
    {
        if (! ($this->getSecurityFacade()->getUser() instanceof UsersInterface)) {
            throw new AccessDeniedException;
        }
    }

    /**
     * @Route('/profile')
     */
    public function indexAction()
    {
        $this->responseView('profile');
    }

    /**
     * @Route('/profile/create')
     */
    public function createAction()
    {
        $response = \View::factory('create-vpn')
            ->set('vpn', (new VpnRepository())->getVpnStatus());

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
            ->set('link', $info[0]['specifications_link']);

        $this->getResponse()->body($response);
    }

    /**
     * @Route('/profile/billing')
     */
    public function billingAction()
    {
        $user = $this->getSecurityFacade()->getUser();

        $this->setJsonResponse(
            (new UserRepository())->getTrafficMeters($user->getId())
        );
    }

    /**
     * @Route('/profile/settings')
     */
    public function settingsAction()
    {
        $user = $this->getSecurityFacade()->getUser();
        $view = \View::factory('settings')
            ->set('email', $user->getEmail())
            ->set('active_vpn', (new UserRepository())->getUserVpnList($user->getId()));
        
        $this->getResponse()->body($view);
    }
}