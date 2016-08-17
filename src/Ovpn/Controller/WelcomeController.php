<?php
namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Security\SecurityFacade;

class WelcomeController extends Controller
{

	public function indexAction()
	{
        $this->getResponse()->body(\View::factory('index'));
	}

    /**
     * @return SecurityFacade
     */
    protected function getSecurityFacade()
    {
        return $this->getContainer()->get('ovpn_security');
    }
}