<?php 
namespace Ovpn\Model;

use Guzzle\Http\Exception\CurlException;
use Mailgun\Mailgun;
use Ovpn\Core\Config;
use Ovpn\Entity\Roles;
use Ovpn\Entity\UsersIntrface;
use Ovpn\Entity\Users;
use DB;
use View;
use Kohana;
use Session;
use Request;
use URL;
use Database;
use Validation;
use Text;


class UserManager
{
    /**
     * @var UsersIntrface
     */
    protected $abstractUser;

    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function setUser(UsersIntrface $user)
    {
        $this->abstractUser = $user;
    }
    

    public function getUserAmount()
    {
        return DB::query(Database::SELECT, 
            "SELECT sum(amount) as amount from billing where uid = :uid")
            ->param(':uid', $this->abstractUser->getId())
            ->execute()
            ->get('amount');
    }

    public function getUserTraffic($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d H:i:s', time() - 2592000);
        }

        return DB::query(Database::SELECT, 
            "SELECT sum(count) as count from traffic where uid = :uid and date > :dt")
            ->param(':uid', $this->abstractUser->getId())
            ->param(':dt', $date)
            ->execute()
            ->get('count');
    }

    /**
     * @return bool|string
     */
    public function allowUserConnect()
    {
        /** @var Users $user */
        $user = $this->abstractUser;
        
        if ($this->getUserTraffic() > $user->getRole()->getTrafficLimit()) {
            return Kohana::message('user', 'fullTrafficOut');
        }

        if ($this->getUserAmount() < $user->getRole()->getMinBalance()) {
            return Kohana::message('user', 'creditOut');
        }

        return true;
    }

    /**
     * @param $post
     * @return array
     * @throws \Kohana_Exception
     * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
     * todo:: must be refactoring
     */
    public function createUser($post)
    {
        if ($this->_config->captcha->check && !$this->recaptcha()) {
            return array(
                'error'   => true,
                'message' => [Kohana::message('user', 'captchaErr')],
                );
        }

        $postValid = Validation::factory($post);

        $postValid->rule('email', 'email')
            ->rule('email', 'not_empty')
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('password', 'not_empty');

        if (!$postValid->check()) {
            return array(
                'error'   => true,
                'message' => array_values($postValid->errors('')),
            );
        }

        /** @var Users $userAlreadyExist */
        $userAlreadyExist = (new Users)
            ->where('email', '=', $post['email'])
            ->find();

        if ($userAlreadyExist->getId() !== null) {
            return array(
                'error'   => true,
                'message' => [Kohana::message('user', 'emailErr')],
            );
        }


        $role = ($post['role'] == 'free')? (new Roles(1)) : (new Roles(2));

        $user = new Users();
        $user
            ->setEmail($post['email'])
            ->setPassword($post['password'])
            ->setRole($role)
            ->setDate()
            ->setLastLogin()
            ->setChecked(false)
            ->setToken(Text::random('alnum', 16));

        $message = View::factory('mail/mailVerify')
            ->set('src',  URL::base(true) . "user/verify/" . $user->getToken());
        $subject = Kohana::message('user', 'mailVerify');

        $mailgun = new Mailgun($this->config->get('mailgun_key'));
    
        try {
            $mailgun->sendMessage('okvpn.org', array(
                'from'    => 'OkVPN <noreply@okvpn.org>',
                'to'      => $user->getEmail(),
                'subject' => $subject,
                'html'    => $message,
            ));

        } catch (CurlException $e) {
            return array(
                'error'   => true,
                'message' => ['mail err'],
            );
        }

        $user->save();

        return array(
            'error'   => false,
            'message' => [Kohana::message('user', 'finish')],
        );
    }

    public function userCheckEmail($token)
    {
        /** @var Users $user */
        $user = (new Users)
            ->where('token', '=', $token)
            ->find();

        if ($user->getId() === null || $user->getChecked()) {
            return false;
        }

        $user->setToken(Text::random('alnum', 16))
            ->setChecked(true);
        $user->save();
        return $user;
    }
    

    public function delete()
    {
        $uid = $this->abstractUser->getId();

        DB::query(Database::DELETE,
            DB::expr("SELECT dropUserData('$uid')"))
            ->execute();
        //todo: must be refactoring in 2.1 SOLID
        setcookie('rememberme', '', 0, '/');
        return true;
    }

    /**
     * @return bool
     * @deprecated since 2.0 and will be removed in 2.1
     * todo:: SOLID
     */
    public function recaptcha()
    {
        $session = Session::instance();
        
        if ($session->get('captcha') == true &&
            $session->get('captchaCount') < 5) {

            $session->set('captchaCount', (int) $session->get('captchaCount') + 1);
            return true;
        }

        $ch = curl_init($this->_config->captcha->api);

        $form = [
            'secret'   => $this->_config->captcha->secret,
            'response' => Request::current()->post('g-recaptcha-response'),
        ];

        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => http_build_query($form),
        ));
        $json = curl_exec($ch);
        $json = json_decode($json);

        if (isset($json->success) && $json->success) {
            $session->set('captcha', true);
            $session->set('captchaCount', 0);
            return true;
        }
        return false;
    }

}