<?php 
namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Repository\UserRepository;

class TestController extends Controller
{
    public function indexAction()
    {
        $ur = new UserRepository();
        var_dump($ur->isAllowConnection('41','nl2-3368cf13'));
    }
}