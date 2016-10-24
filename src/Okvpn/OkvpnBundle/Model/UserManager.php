<?php

namespace Okvpn\OkvpnBundle\Model;

use Okvpn\KohanaProxy\Database;
use Okvpn\KohanaProxy\DB;
use Okvpn\KohanaProxy\Kohana;
use Okvpn\KohanaProxy\View;
use Okvpn\KohanaProxy\Validation;
use Okvpn\KohanaProxy\Text;
use Okvpn\KohanaProxy\URL;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Entity\Roles;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Entity\UsersInterface;
use Okvpn\OkvpnBundle\Filter\UserFilter;
use Okvpn\OkvpnBundle\Repository\UserRepository;
use Okvpn\OkvpnBundle\Tools\MailerInterface;
use Okvpn\OkvpnBundle\Tools\Openvpn\OpenvpnFacade;
use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManagerInterface;
use Okvpn\OkvpnBundle\Tools\Recaptcha;

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

    /**
     * @var Recaptcha
     */
    protected $recaptcha;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserFilter
     */
    protected $userFilter;

    public function __construct(
        Config $config,
        MailerInterface $mailer,
        RsaManagerInterface $rsa,
        UserRepository $userRepository,
        Recaptcha $recaptcha,
        UserFilter $userFilter
    ) {
        $this->config     = $config;
        $this->mailer     = $mailer;
        $this->openvpnRsa = $rsa;
        $this->userRepository = $userRepository;
        $this->recaptcha = $recaptcha;
        $this->userFilter = $userFilter;
    }

    public function getUserAmount(UsersInterface $user)
    {
        return DB::query(
            Database::SELECT,
            "SELECT sum(amount) as amount from billing where uid = :uid"
        )
            ->param(':uid', $user->getId())
            ->execute()
            ->get('amount');
    }

    /**
     * Set user token and send notify on email
     *
     * @param $email
     * @return bool
     */
    public function setUserToken($email)
    {
        $user = $this->userRepository->findUserByEmail($email, true);
        
        if (! $user instanceof UsersInterface) {
            return false;
        }
        
        $token = Text::random('alnum', 16);
        $user->setToken($token);

        $message = View::factory('mail/resetPassword')
            ->set('src', URL::base(true) . "user/resetpassword/" . $user->getToken());
        $subject = Kohana::message('user', 'resetPassword');

        try {
            $this->mailer->sendMessage(
                [
                    'to'      => $email,
                    'subject' => $subject,
                    'html'    => $message
                ]
            );
            $user->save();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Reset user password
     *
     * @param string $conformToken
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword($conformToken, $newPassword)
    {
        $user = $this->userRepository->findUserByToken($conformToken);

        if (! $user instanceof UsersInterface) {
            return false;
        }
        try {
            $user->setPassword($newPassword);
            $user->setToken(null);
            $user->save();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getUserTraffic(UsersInterface $user, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d H:i:s', time() - 2592000);
        }

        return DB::query(
            Database::SELECT,
            "SELECT sum(count) as count from traffic where uid = :uid and date > :dt"
        )
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
            ! $this->recaptcha->check($post['g-recaptcha-response'])) {
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

        $role = (isset($post['role']) && $post['role'] == 'free') ? (new Roles('free')) : (new Roles('full'));

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
            ->set('src', URL::base(true) . "user/verify/" . $user->getToken());
        $subject = Kohana::message('user', 'mailVerify');
        
        try {
            $this->mailer->sendMessage(
                [
                    'to' => $user->getEmail(),
                    'subject' => $subject,
                    'html'    => $message,
                ]
            );
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

    /**
     * @param Users $user
     * @return bool
     */
    public function delete(Users $user)
    {
        $this->getDatabaseManager()->begin();
        
        try {
            $user->setEmail($user->getEmail() . '@delete');
            $user->setChecked(false);
            $user->save();
            $this->userRepository->deleteAllUserVpn($user->getId());
            $this->getDatabaseManager()->commit();
        } catch (\Exception $e) {
            $this->getDatabaseManager()->rollback();
            return false;
        }
        return true;
    }

    public function updateUser(Users $user, $post)
    {
        $postValid = Validation::factory($post);

        $postValid
            ->rule('email', 'email')
            ->rule('email', 'not_empty')
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('password', 'not_empty')
            ->rule('re_password', 'not_empty')
            ->rule('re_password', 'matches', [':validation', 're_password', 'password']);

        if (!$postValid->check()) {
            return [
                'error'   => true,
                'message' => array_values($postValid->errors('')),
            ];
        }

        $user->setPassword($post['password'])
            ->setEmail($post['email']);
        $user->save();

        return [
            'error'   => false,
            'message' => [],
        ];
    }
    
    public function createNamedVpn()
    {
        
    }

    /**
     * Get database instance class
     *
     * @return Database
     * @throws \Kohana_Exception
     */
    private function getDatabaseManager()
    {
        return Database::instance();
    }
}
