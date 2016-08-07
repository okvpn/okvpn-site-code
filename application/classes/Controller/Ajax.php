<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{

    public function action_api()
    {
        $user = Model::factory('User');

        $config = Kohana::$config->load('info');
        $data   = array(
            'auth'    => $user->auth(),
            'signup'  => URL::base() . 'sign',
            'sitekey' => $user->getSiteKey(),
            'login'   => URL::base() . 'user/login',
            'profile' => URL::base() . 'profile',
        );

        $this->response->headers('Content-type', 'application/json')
            ->body(json_encode($data));
    }

    public function action_billing()
    {
        $user = Model::factory('User');

        if ($user->auth() && $user->check_csrf($this->request->post('csrf'), false)) {

            $this->response->headers('Content-Type', 'application/json');
            $this->response->body(json_encode($user->usage()));
        } else {

            throw new HTTP_Exception_404();
        }
    }

    public function action_getinfovpn()
    {
        $user = Model::factory('User');
        if ($user->auth()) {

            $view = View::factory('ajax/vpninfo');
            $id = $this->request->param('token');
            $info = Model::factory('Server')
                ->getVpnInfo($id);
            if (empty($info)) {
                throw new HTTP_Exception_404();
            }

            $this->response->body(View::factory('ajax/vpninfo')
                    ->set('network', preg_replace('/\n/', "<br>", $info[0]['network']))
                    ->set('link', $info[0]['specifications_link'])
                    ->set('csrf', $user->set_csrf())
                    ->set('id', $id)
                );
        } else {
            throw new HTTP_Exception_403();
        }
    }

}
