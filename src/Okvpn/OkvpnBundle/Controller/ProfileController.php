<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;
use Okvpn\OkvpnBundle\Core\HTTPFoundation\AccessDeniedException;
use Okvpn\OkvpnBundle\Entity\UsersInterface;
use Okvpn\OkvpnBundle\Repository\UserRepository;
use Okvpn\OkvpnBundle\Repository\VpnRepository;

class ProfileController extends Controller
{
    use GetSecurityTrait;
    
    /**
     * @inheritdoc
     */
    public function before()
    {
        if (!$this->getSecurityFacade()->getUser() instanceof UsersInterface) {
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
     * @Route('/profile/vpncreate')
     */
    public function vpnCreateAction()
    {
        $this->responseView(
            'create-vpn', 
            [
                'vpn' => (new VpnRepository())->getVpnStatus()
            ]
        );
    }

    /**
     * @Route('/profile/activate')
     */
    public function activateAction()
    {
        
    }

    /**
     * @Route('/profile/getinfovpn/{id}')
     */
    public function getInfoVpnAction()
    {
        $token = $this->getRequest()->param('token');
        $info = (new VpnRepository())->getVpnInformation($token);
        
        $this->responseView(
            'ajax/vpninfo',
            [
                'id' => $token,
                'network' => preg_replace('/\n/', "<br>", $info[0]['network']),
                'link' => $info[0]['specifications_link']
            ]
        );
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
     * @Route('/profile/update')
     */
    public function updateAction()
    {
        $this->responseView('');
    }

    /**
     * @Route('/profile/delete')
     */
    public function deleteAction()
    {
        $this->securityFacade->doLogout();
        $this->redirect();
    }

    /**
     * @Route('/profile/settings')
     */
    public function settingsAction()
    {
        $user = $this->getSecurityFacade()->getUser();

        $this->responseView(
            'settings',
            [
                'email' => $user->getEmail(),
                'active_vpn' => (new UserRepository())->getUserVpnList($user->getId())
            ]
        );
    }
}
