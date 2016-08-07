<?php defined('SYSPATH') or die('No direct script access.');
use Mailgun\Mailgun;

class Controller_User extends Controller {


    /**
     * подтвержления почты
     * 
     */
    public function action_verify()
    {
        $user = Model::factory('User');
        $session = Session::instance();
        if ($user->emailVerify($this->request->param('token'), $session->get('email'))) {
            $this->response->headers('Location',URL::base().'profile/create');
            
        } else {

            throw new HTTP_Exception_404();
        }
    }

    public function action_resettings()
    {
        $user = Model::factory('User');
        if ($user->auth()) {
            if (!$user->check_csrf($this->request->post('csrf'), false)) {
                throw new HTTP_Exception_500();
            }
            $this->response->headers('Content-type','application/json');
            $this->response->body(
                json_encode());
        } else {
            throw new HTTP_Exception_401();
        }
    }

    /**
     * callback для блокчейна 
     * 
     */
    public function action_lister()
    {
        $user = Model::factory('User');
       
    }

    public function action_login()
    {
        $user = Model::factory("User");

        $this->response->headers('Content-type','application/json');
        $this->response->body(json_encode($user->login(
            $this->request->post('password'), $this->request->post('email')
            )));
    }


    public function action_delete()
    {
        $user = Model::factory('User');

        if ($user->auth()) {

            if ($this->request->post('action') == 'delete' && 
                $user->check_csrf($this->request->post('csrf'))
                ) {

                $user->delete();
                $return = array('error' => false);
                $this->response->body(json_encode(array('error' => false)));
            } else {

                $this->response->headers('Content-type','application/json');
                $this->response->body(json_encode(array('error' => true)));
            }

        } else {
            throw new HTTP_Exception_401();
        }
    }

    public function action_createvpn()
    {
        $user = Model::factory('User');

        if ($user->auth()) {
            if (!$user->check_csrf($this->request->post('csrf'))) {
                $code = Kohana::message('user','csrf');
                $error = true;
            } else {
                $code = Model::factory('Server')
                    ->vpnRegi($user, $this->request->post("id"));
                $error = ($code !== false);
            }

            $this->response->headers('Content-type','application/json');
            $this->response->body(json_encode(
                array(
                    'error' =>$error,
                    'message'=>$code,
                )));

        } else {
            throw new HTTP_Exception_401();
        }
    }

    public function action_callbackvpn()
    {
        $token = $this->request->param('token');
        Model::factory('Server')->setRegiVpn($token);
    }

    public function action_listvpn()
    {
        $user = Model::factory('User');
        $user->auth();

        Model::factory('Server')
            ->deleteVpnByList(array(20,18), $user);
    }

    public function action_traffic() 
    {
        $server = Model::factory('Server');
        $server->setTrafficMeters();
    }

    public function action_connect() 
    {
        $server = Model::factory('Server');
        $ip = Request::$client_ip;
        $server->setUserConnect($ip,  
            $this->request->post('name'), 
            $this->request->post('type'));
    }

    public function action_logout()
    {
        setcookie('rememberme','',0,'/');
        $this->response->headers('Location',URL::base());
    }

    public function action_vpndelete()
    {
        $user = Model::factory('User');

        if ($user->auth()) {
            $arr = $this->request->post('host');
            if (($arr = json_decode(base64_decode($arr))) && $user
                    ->check_csrf($this->request->post('csrf'), false)) {

                Model::factory('Server')
                    ->deleteVpnByList($arr, $user);
                $ans = array('error' => false);

            } else {
                $ans = array('error' => true);
            }

            $this->response->headers('Content-type','application/json')
                ->body(json_encode($ans));

        } else {
            throw new HTTP_Exception_401();
        }
       
    }

    public function action_p0f()
    {
        $p0f = Okvpn::getP0fData();
        if ($p0f) {
            foreach ($p0f as $key => $value) {
                echo "<pre>$key    $value</pre>".PHP_EOL;
            }
        } else {
            echo "false";
        }
    }

}