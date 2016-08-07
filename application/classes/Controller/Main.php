<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller
{

    public function action_faq()
    {
        $user = Model::factory('User');

        $this->response->body(
            View::factory('faq')
                ->set('auth', $user->auth()));
    }

    public function action_proxy()
    {
        $user = Model::factory('User');
        $this->response->body(View::factory('proxy')
            ->set('auth',$user->auth())
            );
    }

    public function action_guide()
    {
        $user = Model::factory('User');
        $this->response->body(View::factory('userguide')
                ->set('auth', $user->auth())
        );
    }

    public function action_content()
    {
        $user = Model::factory('User');
        $this->response->body(View::factory('self')
                ->set('auth', $user->auth())
        );
    }

    public function action_test()
    {
        $user = Model::factory('user');
    }

    public function action_sign()
    {
        $sign = Model::factory('User')->create();
        if ($sign['error'] == false) {
            $session = Session::instance();
            $session->set('email', $this->request->post('email'));
        }
        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode($sign));
    }

    public function action_blockchain()
    {
        $token = $this->request->param('token');
        $user  = Model::factory('User');

    }

}
