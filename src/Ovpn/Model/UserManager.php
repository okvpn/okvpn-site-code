<?php 
namespace Ovpn\Model;

use Ovpn\Core\Config;
use Ovpn\Entity\Roles;
use Ovpn\Entity\Users;
use Ovpn\Entity\UsersInterface;
use DB;
use Ovpn\Tools\MailerInterface;
use Ovpn\Tools\Openvpn\OpenvpnFacade;
use Ovpn\Tools\Openvpn\RsaManagerInterface;
use Ovpn\Tools\Recaptcha;
use View;
use Kohana;
use URL;
use Database;
use Validation;
use Text;

class UserManager
{
    
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var OpenvpnFacade
     */
    protected $openvpnRsa;

    public function __construct(Config $config, MailerInterface $mailer, RsaManagerInterface $rsa)
    {
        $this->config     = $config;
        $this->mailer     = $mailer;
        $this->openvpnRsa = $rsa;
    }
    

    public function getUserAmount(UsersInterface $user)
    {
        return DB::query(Database::SELECT, 
            "SELECT sum(amount) as amount from billing where uid = :uid")
            ->param(':uid', $user->getId())
            ->execute()
            ->get('amount');
    }

    public function getUserTraffic(UsersInterface $user, $date = null)
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
     * @param UsersInterface $user
     * @return bool|string
     */
    public function allowUserConnect(UsersInterface $user)
    {
        if ($this->getUserTraffic($user) > $user->getRole()->getTrafficLimit()) {
            return Kohana::message('user', 'fullTrafficOut');
        }

        if ($this->getUserAmount($user) < $user->getRole()->getMinBalance()) {
            return Kohana::message('user', 'creditOut');
        }

        return true;
    }

    /**
     * @param $post
     * @return array
     * @throws \Kohana_Exception
     * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
     */
    public function createUser($post)
    {
        $postValid = Validation::factory($post);

        $postValid->rule('email', 'email')
            ->rule('email', 'not_empty')
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('password', 'not_empty')
            ->rule('g-recaptcha-response', 'not_empty');

        if (!$postValid->check()) {
            return [
                'error'   => true,
                'message' => array_values($postValid->errors('')),
            ];
        }
        
        if ($this->config->get('captcha:check') &&
            ! Recaptcha::check($post['g-recaptcha-response'])) {
            return [
                'error'   => true,
                'message' => [Kohana::message('user', 'captchaErr')],
                ];
        }

        /** @var Users $userAlreadyExist */
        $userAlreadyExist = (new Users)
            ->where('email', '=', $post['email'])
            ->find();

        if (null !== $userAlreadyExist->getId()) {
            return [
                'error'   => true,
                'message' => [Kohana::message('user', 'emailErr')],
            ];
        }

        $role = ($post['role'] == 'free') ? (new Roles(1)) : (new Roles(2));

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
        
        try {
            $this->mailer->sendMessage([
                'to' => $user->getEmail(),
                'subject' => $subject,
                'html'    => $message,
            ]);

        } catch (\Exception $e) {
            return [
                'error'   => true,
                'message' => ['mail err'],
            ];
        }

        $user->save();

        return [
            'error'   => false,
            'message' => [Kohana::message('user', 'finish')],
        ];
    }

    /**
     * @param $token
     * @return bool|Users
     * @throws \Kohana_Exception
     */
    public function confirmEmail($token)
    {
        /** @var Users $user */
        $user = (new Users)
            ->where('token', '=', $token)
            ->and_where('checked', '=', false)
            ->find();

        if ($user->getId() === null || $user->getChecked()) {
            return false;
        }

        $user->setToken(Text::random('alnum', 16))
            ->setChecked(true);
        $user->save();
        return $user;
    }
    

    public function delete(UsersInterface $user)
    {
        $uid = $user->getId();

        DB::query(Database::DELETE,
            DB::expr("SELECT dropUserData('$uid')"))
            ->execute();
        //todo: must be refactoring in 2.1 SOLID
        setcookie('rememberme', '', 0, '/');
        return true;
    }

}