<?php // @codingStandardsIgnoreStart
namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;


class TestController extends Controller
{
    public function indexAction()
    {
        $logger = $this->container->get('logger');
        $logger->addInfo('test');
    }
}
// @codingStandardsIgnoreEnd
