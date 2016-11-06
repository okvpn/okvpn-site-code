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
     * @Route('/profile/viewvpn')
     */
    public function viewVpnAction()
    {
        $this->responseView(
            'create-vpn',
            [
                'vpn' => (new VpnRepository())->getVpnStatus()
            ]
        );
    }

    /**
     * @Route('/profile/activate/{host}')
     */
    public function activateAction()
    {
        $userManager = $this->container->get('ovpn_user.manager');

        $this->setJsonResponse(
            $userManager->activateVpn(
                $this->getSecurityFacade()->getUser(),
                $this->getRequest()->param('token')
            )
        );
    }

    /**
     * @Route('/profile/deleteitemsvpn')
     */
    public function deleteItemsVpnAction()
    {
        $data = $this->getRequest()->post('hosts');
        if ($data = json_decode($data, true) and is_array($data)) {
            $this->container->get('ovpn_vpn.manager')->deleteVpnItemsByList($data);
        }
        $this->setJsonResponse(['error' => !is_array($data)]);
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
        $userManager = $this->container->get('ovpn_user.manager');
        $this->setJsonResponse(
            $userManager->updateUser(
                $this->securityFacade->getUser(),
                $this->getRequest()->post()
            )
        );
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
