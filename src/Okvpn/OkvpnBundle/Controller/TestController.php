<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;
use Okvpn\OkvpnBundle\Entity\Balance;

class TestController extends Controller
{
    public function indexAction()
    {
        $balance = new Balance(1);
        $user = $balance->getUser();
    }
}
