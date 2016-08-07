<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Profile extends Controller
{

    public function action_index()
    {
        $user = Model::factory('User');
        if ($user->auth()) {

            $this->response->body(
                View::factory('profile')
                    ->set('csrf', $user->set_csrf(false)));
        } else {
            $this->response->headers('Location', URL::base());
        }
    }

    public function action_wallet()
    {
        $user = Model::factory('User');

        if ($user->auth()) {

            $user->instance();
            $wallet = $user->_wallet;
            $rate   = round(Okvpn::get_var('btc_rate'), 1);
            $view   = View::factory('wallet')
                ->set('wallet', $wallet)
                ->set('btc', $rate);
            $this->response->body($view);
        } else {

            $this->response->headers('Location', URL::base());
        }
    }

    public function action_settings()
    {
        $user = Model::factory('User');

        if ($user->auth()) {
            $user->instance();
            $listActiv = Model::factory('Server')
                ->getUserVpn($user);

            $view = View::factory('settings')
                ->set('email', $user->getEmail())
                ->set('csrf', $user->set_csrf())
                ->set('active_vpn', $listActiv);
            $this->response->body($view);

        } else {

            $this->response->headers('Location', URL::base());
        }
    }

    public function action_create()
    {
        $user = Model::factory('User');

        if ($user->auth()) {
            // var_dump($user->vpn_list());
            $view = View::factory('create-vpn')
                ->set('vpn', Model::factory('Server')->getVpns())
                ->set('csrf', $user->set_csrf());

            $this->response->body($view);

        } else {

            $this->response->headers('Location', URL::base());
        }
    }

}
