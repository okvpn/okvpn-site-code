<?php 
namespace Ovpn\Model;

use Guzzle\Http\Exception\CurlException;
use Mailgun\Mailgun;
use Ovpn\Entity\Roles;
use Ovpn\Entity\UsersIntrface;
use Ovpn\Entity\Users;
use DB;
use View;
use Kohana;
use Session;
use Request;
use Cookie;
use URL;
use Database;
use Validation;
use Text;
use Model;


class UserManager extends Model
{
    protected $_config;

    /**
     * @var UsersIntrface
     */
    protected $_user;

    protected $_session;

    public function __construct(UsersIntrface $user)
    {
        $this->_user = $user;
        $mode = MODE;
        $this->_config = Kohana::$config
            ->load('info')->$mode;

        $this->_session = Session::instance();
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->_user;
    }

    public function setUser(UsersIntrface $user)
    {
        $this->_user = $user;
    }

    /**
     * @param $email string
     *
     * @return  Users | null
     */
    public static function findUserByEmail($email)
    {
        /** @var Users $user */
        $user = (new Users())
            ->where('email', '=', $email)
            ->find();

        if ($user->getId() === null) {
            return null;
        }

        return $user;
    }

    /**
     *
     * @param $pass string
     * @return array|bool
     *
     * @deprecated
     */
    public function doLogin($pass)
    {
        /** @var Users $user*/
        $user = $this->getUser();
        if ($user->getId() === null) {
            return array(
                'error'   => true,
                'message' => array(Kohana::message('user', 'accountNotFound')),
            );
        }

        if (!password_verify($pass, $user->getPassword())) {
            return array(
                'error'   => true,
                'message' => array(Kohana::message('user', 'accountNotFound')),
            );
        }

        $this->authorizate();

        return true;
    }

    /**
     * @return $this
     * @deprecated
     */
    public function secureContext()
    {
        if ($this->_user) {
            return $this;
        }

        if ($this->_session->get('user') === null) {
            $userInfo = Cookie::get('rememberme');

            if ($userInfo = base64_decode($userInfo) and $userInfo = json_decode($userInfo, true)) {
                $user = new Users($userInfo['id']);
                
                if (hash('sha512', $user->getToken()) != $userInfo['t']) {
                    $user = null;
                }

            } else {
                $user = null;
            }
        } else {
            $user = new Users($this->_session->get('user'));
        }

        if ($user !== null) {
            $this->_session->set('user', $user->getId());
        }

        $this->_user = $user;
        return $this;
    }

    /**
     * @param bool $new
     * @return mixed|string
     * @deprecated
     */
    public function setCsrfToken($new = true)
    {
        $token   = Text::random('alnum', 12);
        
        if (!$new && $this->_session->get('csrf') != null) {
            return $this->_session->get('csrf');
        }
        $this->_session->set('csrf', $token);
        return $token;
    }

    /**
     * @param $token
     * @param bool $instance
     * @return bool
     * @deprecated 
     */
    public function checkCsrfToken($token, $instance = false)
    {
        if ($instance) {
            $this->setCsrfToken();
        }

        return ($this->_session->get('csrf') == $token);
    }

    public function getUserAmount(UsersIntrface $user)
    {
        return DB::query(Database::SELECT, 
            "SELECT sum(amount) as amount from billing where uid = :uid")
            ->param(':uid', $user->getId())
            ->execute()
            ->get('amount');
    }

    public function getUserTraffic(UsersIntrface $user, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d H:i:s', time() - 2592000);
        }

        return DB::query(Database::SELECT, 
            "SELECT sum(count) as count from traffic where uid = :uid and date > :dt")
            ->param(':uid', $user->getId())
            ->param(':dt', $date)
            ->execute()
            ->get('count');
    }

    /**
     * @param UsersIntrface $user
     * @return bool|string
     */
    public function allowUserConnect(UsersIntrface $user)
    {
        /** @var Users $user */
        if ($this->getUserTraffic($user) > $user->getRole()->getTrafficLimit()) {
            return Kohana::message('user', 'fullTrafficOut');
        }

        if ($this->getUserAmount($user) < $user->getRole()->getMinBalance()) {
            return Kohana::message('user', 'creditOut');
        }

        return true;
    }

    public function isGranted($roleName)
    {
        $name = unserialize($this->getUser()->getRole()->getRoleName());
        return in_array($roleName, $name);
    }

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

        $mailgun = new Mailgun($this->_config->mailkey);
    
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


    public function getTrafficMeters(UsersIntrface $user)
    {
        return DB::query(Database::SELECT, 
                "SELECT CAST(row_number() OVER() as integer) as id, 
                    CAST(r1.dates as character varying) as date, 
                    r1.traffic as x, r1.amount as spent,
                COALESCE((select sum(b.amount) from billing as b
                    where b.uid = :idd and b.date < (select date_trunc('day',now() - interval '1 month'))),
                0) + 
                sum(r1.amount) OVER (ORDER BY r1.dates)  as balance from (
                    select t2.dates, COALESCE(t1.traffic,0) as traffic, COALESCE(t3.amount,0) as amount from (
                        select sum(t.count) as traffic, TO_CHAR(t.date,'YYYY-MM-DD') as dates
                            from traffic as t where uid = :idd group by dates) t1
                    right join (
                        select TO_CHAR(current_date - rng,'YYYY-MM-DD') as dates
                            from generate_series(0,30,1) as rng) t2
                    on t1.dates = t2.dates
                    left join (
                        select sum(b.amount) as amount, TO_CHAR(b.date,'YYYY-MM-DD') as dates
                            from billing as b where uid = :idd group by dates) t3
                    on t3.dates = t2.dates) r1")
            ->param(':idd', $user->getId())
            ->execute()
            ->as_array();
    }

    public function delete(UsersIntrface $user)
    {
        $uid = $user->getId();

        DB::query(Database::DELETE,
            DB::expr("SELECT dropUserData('$uid')"))
            ->execute();

        setcookie('rememberme', '', 0, '/');
        return true;
    }

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

    public function authorizate()
    {
        $user = $this->getUser();

        if ($user->getToken() == null) {
            $user->setToken(Text::random('alnum', 16));
            $user->save();
        }

        $this->_session->set('user', $user->getId());

        $rememberme = base64_encode(json_encode(
            ['id' => $user->getId(), 't' => hash('sha512', $user->getToken()), 'time' => time()]));

        Cookie::set('rememberme', $rememberme);
    }

}