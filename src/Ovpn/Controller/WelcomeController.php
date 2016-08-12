<?php
namespace Ovpn\Controller;

use Annotations\DependencyInjectionAnnotation as DI;
use Controller;
use Ovpn\Model\UserManager;
use Symfony\Component\DependencyInjection\Container;


class WelcomeController extends Controller
{
    
    /**
     * @var Container
     *
     * @DI(service="container")
     */
	protected $container;

	public function action_index()
	{
		$this->response->body(\View::factory('index'));
	}
    
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('ovpn_user.manager');
    }
}