<?php

namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;;

class TestController extends Controller
{
    public function indexAction()
    {
//        $ur = $this->container->get('ovpn_user.repository');
//        var_dump($ur->isAllowCreateVpnSelected(2, 1));
        $t = $this->container->get('ovpn_openvpn.config.factory');
        $tcp = $t->create('tcp');
        var_dump($tcp->createOpenvpnConfiguration(time(), 'pa1'));
    }
}
