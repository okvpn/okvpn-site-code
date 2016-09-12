<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Entity\Users;

class WelcomeController extends Controller
{

	public function indexAction()
	{
		$tr = new Users(1);
		$tr->setEmail('test1');
		$tr->save();

        $this->getResponse()->body(\View::factory('index'));
	}
}