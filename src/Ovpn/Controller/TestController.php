<?php 
namespace Ovpn\Controller;

use Ovpn\Core\Controller;

class TestController extends Controller
{
    public function indexAction()
    {
        $el = $this->getContainer()->get('ovpn_security');
        var_dump($el);
    }
}