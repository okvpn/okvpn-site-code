<?php 
namespace Ovpn\Controller;

use Ovpn\Model\UserManager;
use URL;
use Kohana;
use View;
use ORM;

class ProfileController extends \Controller
{
    protected $_user;

    protected $_userManager;

    public function __construct(Request $request, Response $response)
    {
        $this->_userManager = new UserManager();

        $user = $this->_userManager->secureContext()->getUser();

        if ($user === null) {
            throw new HTTP_Exception_401();
        }

        $this->_user = $user;
        parent::__construct($request, $response);
    }

    public function action_index()
    {
        $this->response->body(View::factory('profile')
                ->set('csrf', $this->_userManager->setCsrfToken(false)));
    }

    public function action_settings()
    {

        $listActiv = Model::factory('Server')->getUserVpn($this->_user);

        $view = View::factory('settings')
            ->set('email', $this->_user->getEmail())
            ->set('csrf', $this->_userManager->setCsrfToken())
            ->set('active_vpn', $listActiv);

        $this->response->body($view);
    }

    public function action_create()
    {

        $view = View::factory('create-vpn')
            ->set('vpn', Model::factory('Server')->getVpns())
            ->set('csrf', $this->_userManager->setCsrfToken(false));

        $this->response->body($view);
    }

    public function action_wallet()
    {
        $view = View::factory('wallet')
            ->set('allow', Model_Bitpay::$allowSumPaid);

        $this->response->body($view);
    }

}
