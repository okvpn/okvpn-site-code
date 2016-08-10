<?php
//TODO:: could be refactored in v2
namespace Ovpn\Controller;

use URL;
use Kohana;
use View;
use ORM;

class MainController extends \Controller
{

    public function action_faq()
    {

        $this->response->body(
            View::factory('faq')
                ->set('auth', false));
    }

    public function action_proxy()
    {
        /** @var $user Model_User*/
        $user = Model::factory('User');
        $this->response->body(View::factory('proxy')
            ->set('auth',$user->auth())
            );
    }

    public function action_guide()
    {
        /** @var $user Model_User*/
        $user = Model::factory('User');
        $this->response->body(View::factory('userguide')
                ->set('auth', $user->auth())
        );
    }

    public function action_signup()
    {
        //TODO:: could be refactored in v2
        $sign = (new Model_UserManager())->createUser($_POST);
      
        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode($sign));
    }

}
