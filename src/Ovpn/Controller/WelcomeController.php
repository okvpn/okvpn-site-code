<?php
namespace Ovpn\Controller;

use Annotations\DependencyInjectionAnnotation as DI;
use Ovpn\Model\UserManager;
use Controller;


class WelcomeController extends Controller
{
    
    /**
     * @DI(service="ovpn_user.manager")
     */
	protected $userManager;

	public function action_index()
	{
		$this->response->body(\View::factory('index'));
	}
    
    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
}