<?php
//TODO:: could be refactored in v2
namespace Ovpn\Controller;

use View;

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
        $this->response->body(View::factory('proxy')
            ->set('auth', false)
            );
    }

    public function action_guide()
    {
        $this->response->body(View::factory('userguide')
                ->set('auth', false)
        );
    }

    public function action_signup()
    {
        //TODO:: could be refactored in v2
        
        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode([]));
    }

}
