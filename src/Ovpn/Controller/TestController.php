<?php 
namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Security\SecurityFacade;

class TestController extends Controller
{
    public function indexAction()
    {
        /** @var SecurityFacade $el */
        $el = $this->getContainer()->get('ovpn_security');
        $el->getUser();
    }
}