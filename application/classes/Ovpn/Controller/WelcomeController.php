<?php
namespace Ovpn\Controller;


class WelcomeController extends \Controller
{

	public function action_index()
	{
		$this->response->body(\View::factory('index'));
	}
}
