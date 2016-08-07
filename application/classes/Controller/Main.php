<?php

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

    public function action_signup()
    {
        $sign = (new Model_UserManager())->createUser($_POST);
      
        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode($sign));
    }

    public function action_blockchain()
    {
        $token = $this->request->param('token');
        $user  = Model::factory('User');

    }

}
