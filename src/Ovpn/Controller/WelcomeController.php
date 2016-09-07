<?php

namespace Ovpn\Controller;

use Ovpn\Core\Controller;

class WelcomeController extends Controller
{

	public function indexAction()
	{
        $this->getResponse()->body(\View::factory('index'));
	}
}