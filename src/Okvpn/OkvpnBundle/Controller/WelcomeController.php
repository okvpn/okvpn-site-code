<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;

class WelcomeController extends Controller
{

    public function indexAction()
    {
        $this->getResponse()->body(\View::factory('index'));
    }
}
