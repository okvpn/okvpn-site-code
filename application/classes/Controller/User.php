<?php
use Mailgun\Mailgun;

class Controller_User extends Controller {

    protected $_user;

    protected $_userManager;


    /**
     * подтвержления почты
     * 
     */
    public function action_verify()
    {
        if (!($user = $this->getUserManager()->userCheckEmail($this->request->param('token')))) {
            throw new HTTP_Exception_404();
        }
        
        $this->getUserManager()->authorizate($user);
        $this->response->headers('Location', URL::base().'profile/create');
    }

    public function action_resettings()
    {

    }

    public function action_login()
    {
        $result = $this->getUserManager()->doLogin($this->request->post('email'), 
            $this->request->post('password'));

        $this->response->headers('Content-type','application/json')
            ->body(json_encode($result));
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
        $user = $this->getUser();

        if ($user === null) {
            throw new HTTP_Exception_401();
        }

        if (!$this->getUserManager()->checkCsrfToken($this->request->post('csrf'))) {

            $code = Kohana::message('user','csrf');
            $error = true;

        } else {
            $code = Model::factory('Server')
                ->createClientConfig($user, new Model_Host($this->request->post("id")));
            $error = ($code !== false);
        }

        if (!$error) {
            $this->getUserManager()->setCsrfToken();
        }

        $this->response->headers('Content-type','application/json');
        $this->response->body(json_encode(
            array(
                'error' =>$error,
                'message'=>$code,
            )));
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

        $answer = $server->setUserConnect($ip, $this->request->post('name'), 
            $this->request->post('type'));

        $this->response->headers('Content-type','application/json')
            ->body(json_encode(['allow' => $answer]));
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

    /**
     * bitpay notify url
     *
     */
    public function action_notification_bitpay()
    {
        $tx = ORM::factory('Transaction')
            ->where('notification_url', '=', $this->request->param('token'))
            ->find();

        if (!$tx->getInvoiceId()) {
            throw new HTTP_Exception_404();
        }

        $bitpay = new Model_Bitpay;
        $bitpay->refreshStatus($tx);
    }

    public function action_redirect_url()
    {
        $tx = ORM::factory('Transaction')
            ->where('redirect_url', '=', $this->request->param('token'))
            ->find();

        if (!$tx->getInvoiceId() || !in_array($tx->getStatus(),
            array(Model_Bitpay::STATUS_PAID, Model_Bitpay::STATUS_NEW)) ) {

            throw new HTTP_Exception_404();
        }

        $bitpay = new Model_Bitpay;
       
        $paidSum = $bitpay->getTransactionSum($tx);

        if ($paidSum) {


        } else {

        }
    }

    public function action_create_invoce()
    {
        $user = Model::factory('User');

        if (!$user->auth()) {
            throw new HTTP_Exception_401();
        }

        $sumPaid = $this->request->post('usd');

        $bitpay = new Model_Bitpay;
        $tx = $bitpay->createInvoice($user->getId(), $sumPaid);

        $this->response
            ->headers('Location', Model_Bitpay::BITPAY_URL_INVOICE . $tx->getInvoiceId());
    }

    protected function getUser()
    {
        if ($this->_user !== null) {
            return $this->_user;
        }
        $this->_user = (new Model_UserManager)->secureContext()->getUser();
        return $this->_user;
    }

    protected function getUserManager()
    {
        if ($this->_userManager !== null) {
            return $this->_userManager;
        }
        $this->_userManager = new Model_UserManager(); 
        return $this->_userManager;
    }

}