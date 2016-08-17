<?php
//TODO:: could be refactored in v2
namespace Ovpn\Controller;

use Ovpn\Core\Controller;
use Ovpn\Entity\UsersIntrface;

class MainController extends Controller
{

    use GetSecurityTrait;
    
    public function faqAction()
    {
        $this->getResponse()->body(
            \View::factory('faq')
                ->set('auth', $this->getSecurityFacede()->getUser() instanceof UsersIntrface)
        );
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
    
    /*public function action_signup()
    {
        //TODO:: could be refactored in v2
        
        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode([]));
    }*/

}
