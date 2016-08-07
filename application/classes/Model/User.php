<?php defined('SYSPATH') or die('No direct script access.');


class Model_User extends Model
{

    protected $_config;

    /**
     * поля которые могут быть изменены пользователем
     *
     */
    protected $_uid;

    // email пользователя
    protected $_email;

    //serialize hash and solt
    protected $_password;

    //время последнего входа
    protected $_last_login;

    // дата регистрации 
    protected $_date;

    //токен пользователя служит для потверждения email и приема платижей
    protected $_token;

    //role 1 - free, 2 - full
    protected $_role;

    public function getDate()
    {
        return $this->_date;
    }

    public function setDate($date)
    {
        $this->_date = $date;
        return $this;
    }

    public function getId()
    {
        return $this->_uid;
    }

    public function setId($id)
    {
        $this->_uid = $id;
        return $this;
    }

    public function getSiteKey()
    {
        return $this->_config->captcha->sitekey;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }

    public function setPassword($pass)
    {
        $this->_password = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setLastLogin($date)
    {
        $this->_last_login= $date;
        return $this;
    }

    public function getLastLogin()
    {
        return $this->_last_login;
    }

    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function setRole($role)
    {
        $this->_role = $role;
        return $this;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function __construct()
    {
        $mode = MODE;
        $this->_config = Kohana::$config
            ->load('info')
            ->$mode;
    }

    /**
     * создает аккаунт пользователю
     * @param bool  $free тип аккаунта
     * @return array описание содержащие был ли создан
     *               аккаунт, а если нет то описание ощибок
     */
    public function create()
    {
        $free = Request::current()->post('mode');
        if ($this->_config->captcha->check && !$this->recaptcha()) {
            return array(
                'error'   => true,
                'message' => array(
                    Kohana::message('user', 'captchaErr'),
                ),
            );
        }

        $post = Validation::factory($_POST);
        if ($free) {

            $post->rule('email', 'email')
                ->rule('email', 'not_empty')
                ->rule('password', 'min_length', array(':value', 6))
                ->rule('password', 'not_empty');
        } else {
            $post->rule('email', 'email')
                ->rule('email', 'not_empty')
                ->rule('password', 'min_length', array(':value', 6))
                ->rule('password', 'not_empty');
        }

        if ($post->check()) {
            $post = Request::current()->post();
            $this->setToken(Text::random('alnum', 16))
                ->setEmail(mb_strtolower(Arr::get($post, 'email')))
                ->setPassword(Arr::get($post, 'password'))
                ->setDate(date('Y-m-d H:i:s'))
                ->setLastLogin(date('Y-m-d H:i:s'))
                ->setRole(2);

            if ($free) {
                $this->setRole(1);
            }

            if (
                DB::select(array(DB::expr('COUNT(1)'), 'cnt'))
                ->from('users')
                ->where('email', '=', $this->getEmail())
                ->execute()
                ->get('cnt')
            ) {
                return array(
                    'error'   => true,
                    'message' => array(Kohana::message('user', 'emailErr')),
                );
            }

            DB::insert('users', array(
                'email', 'pass', 'date', 'checked', 'role', 'last_login', 'token'))
            ->values(array(
                $this->getEmail(), $this->getPassword(), $this->getDate(), false,
                $this->getRole(), $this->getLastLogin(), $this->getToken()))
            ->execute();

            $this->mail_cheked();
        } else {

            return array(
                'error'   => true,
                'message' => array_values($post->errors('')),
            );
        }

        $session = Session::instance();
        $session->set('captcha', false);
        return array(
            'error'   => false,
            'message' => array(Kohana::message('user', 'finish')),
        );
    }

    /**
     * формирует выписку баланса пользователя
     * за 30 дней
     * @return array
     */
    public function usage()
    {
        $items = DB::query(Database::SELECT,
            "SELECT * from usageUser(:id)")
            ->param(':id', $this->_uid)
            ->execute()
            ->as_array();

        return $items;
    }

    /**
     * удаляет аккаунт пользователю
     *
     */
    public function delete()
    {
        $uid = $this->getId();

        DB::query(Database::DELETE,
            DB::expr("SELECT dropUserData('$uid')"))
            ->execute();

        setcookie('rememberme', '', 0, '/');
        return true;
    }

    /**
     * проверяет авторизован ли пользователь
     * @return bool
     */
    public function auth()
    {
        $uid = Cookie::get('rememberme');

        if ($uid === null) {
            return false;
        } else {
            $this->setId($uid);
            return true;
        }
    }

    /**
     * заполняет поля значениями
     *
     */
    public function instance()
    {
        if ($this->getId() !== null) {
            $sql = DB::select('email', 'role', 'wallet')->from('users')
                ->where('id', '=', $this->getId())->execute();

            if ($sql->count() == 1) {
                list($this->_email, $this->_role, $this->_wallet) =
                    array_values($sql->as_array()[0]);
                return true;
            }
        }
        return false;
    }

    /**
     * автроизует пользователя
     * @return array('error' => '/true|false/','message' => <описание ошибки>)
     */
    public function login($userPass, $userEmail)
    {
        $post = Validation::factory($_POST);
        $post->rule('password', 'not_empty')
            ->rule('email', 'not_empty');
        if ($post->check()) {
            $this->setEmail($userEmail);
         
            $sql = DB::select('pass', 'id')->from('users')
                ->where('email', '=', $this->getEmail())
                ->and_where('checked', '=', true)->execute();

            $pass = $sql->get("pass");
            $this->setId($sql->get('id'));
    
    
            if ($sql->get("pass") === null) {
                return array(
                    'error'   => true,
                    'message' => array(Kohana::message('user', 'accountNotFound')),
                );
            }
            if (password_verify($userPass, $pass)) {
                Cookie::set('rememberme', $this->_uid);
                DB::update('users')
                    ->set(array('last_login' => date('Y-m-d H:i:s')))
                    ->where('id', '=', $this->_uid)
                    ->execute();

                return array(
                    'error'   => false,
                    'message' => array(),
                );                
            }
        }

        return array(
            'error'   => true,
            'message' => array(Kohana::message('user', 'accountNotFound')),
        );
    }

    public function isAdminRole()
    {
        if (!$this->auth()) {
            return false;
        }

        $this->instance();

        return ($this->getRole() == 3);
    }

    /**
     * проверка ре-капчи
     * возваращает true в случаи если проверка пройдена, else - false
     * @return  bool
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

        $form = array(
            'secret'   => $this->_config
                ->captcha
                ->secret,
            'response' => Request::current()->post('g-recaptcha-response'),
        );

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

    /**
     * отправляет письмо пользователю для потвержления почты
     *
     * @return bool
     */
    public function mail_cheked()
    {
        $message = View::factory('mail/mailVerify')
            ->set('src', $this->_config->site . "/user/verify/$this->_token");
        $subject = Kohana::message('user', 'mailVerify');

        $mailgun = new Mailgun\Mailgun($this->_config->mailkey);
        $domain  = "okvpn.org";
        try {
            $result = $mailgun->sendMessage($domain, array(
                'from'    => 'OkVPN <noreply@okvpn.org>',
                'to'      => $this->getEmail(),
                'subject' => $subject,
                'html'    => $message,
            ));

        } catch (Guzzle\Http\Exception\CurlException $e) {
            return false;
        }

        return (bool) (isset($result->http_response_code) && $result->http_response_code == 200);
    }

    /**
     * подтверждение почты и создает wallet
     * если пользователь подтвердил почту
     * @param  string $token
     * @return bool
     */
    public function emailVerify($token, $email)
    {
        $this->setId(DB::select('id')
            ->from('users')->where('token', '=', $token)
           // ->and_where('email','=',$email)
            ->execute()->get('id'));

        if ($this->getId() === null) {
            return false;
        }

        $sql = DB::update('users')
            ->value('checked', true)
            ->where('token', '=', $token)
            ->execute();
        Cookie::set('rememberme', $this->getId());

        return true;
    }

    public function set_csrf($new = true)
    {
        $token   = Text::random('alnum', 12);
        $session = Session::instance();

        if (!$new && $session->get('csrf') != null) {
            return $session->get('csrf');
        }
        $session->set('csrf', $token);
        return $token;
    }

    public function check_csrf($csrf, $instance = true)
    {
        $session = Session::instance();
        $csrf = ($session->get('csrf') == $csrf);
        if ($instance) {
            $this->set_csrf();
        }
        return (bool)$csrf;
    }

    public function controlUser()
    {
        return DB::query(Database::SELECT,
            DB::expr("select * from controlUser();"))
            ->execute()->as_array();
    }

}
