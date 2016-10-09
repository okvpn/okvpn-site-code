<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Entity\Balance;

class TestController extends Controller
{
    public function indexAction()
    {
        $balance = new Balance(1);
        $user = $balance->getUser();
    }
}
