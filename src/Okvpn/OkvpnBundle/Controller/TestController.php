<?php // @codingStandardsIgnoreStart
namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;;

class TestController extends Controller
{
    public function indexAction()
    {
        echo json_encode(['TCP', 'UPD']);
    }
}
// @codingStandardsIgnoreEnd
