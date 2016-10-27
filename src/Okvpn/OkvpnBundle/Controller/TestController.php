<?php // @codingStandardsIgnoreStart
namespace Okvpn\OkvpnBundle\Controller;

use Okvpn\OkvpnBundle\Core\Controller;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Event\CreateUserEvent;
use Okvpn\OkvpnBundle\Event\UserEvents;

;

class TestController extends Controller
{
    public function indexAction()
    {
        $eventDispatcher = $this->container->get('event_dispatcher');
        $eventDispatcher->dispatch(UserEvents::POST_CREATE_USER, new CreateUserEvent(new Users()));
    }
}
// @codingStandardsIgnoreEnd
