<?php // @codingStandardsIgnoreStart
namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;


class TestController extends Controller
{
    use GetSecurityTrait;

    public function indexAction()
    {
        $t = $this->getSecurityFacade()->getUser();
    }
}
// @codingStandardsIgnoreEnd
