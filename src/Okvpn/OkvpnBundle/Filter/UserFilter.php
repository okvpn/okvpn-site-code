<?php

namespace Okvpn\OkvpnBundle\Filter;

use Okvpn\KohanaProxy\Kohana;
use Okvpn\KohanaProxy\Validation;
use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Entity\UsersInterface;
use Okvpn\OkvpnBundle\Filter\Exception\UserCreateException;
use Okvpn\OkvpnBundle\Repository\UserRepository;
use Okvpn\OkvpnBundle\Tools\Recaptcha;

class UserFilter
{

    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    /** @var Recaptcha  */
    protected $recaptcha;
    
    public function __construct(UserRepository $userRepository, Recaptcha $recaptcha)
    {
        $this->userRepository = $userRepository;
        $this->recaptcha = $recaptcha;
    }

    /**
     * @param UsersInterface $user
     * @param $vpnId
     *
     * @return array
     */
    public function isAllowCreated(UsersInterface $user, $vpnId)
    {
        $result = $this->userRepository->isAllowCreateVpnSelected($user->getId(), $vpnId);
        $errors = [];

        foreach ($result as $item) {
            if ($item['error'] == 't') {
                $errors[] = Kohana::message('user', $item['message']);
            }
        }

        return [
            'error' => !empty($errors),
            'messages' => $errors
        ];
    }

    /**
     * @param array $data
     * @throws UserCreateException
     * @throws \Kohana_Exception
     */
    public function validateUserRegistrationForm(array $data)
    {
        $validator = Validation::factory($data);
        $validator->rule('email', 'email')
            ->rule('email', 'not_empty')
            ->rule('password', 'min_length', array(':value', 6))
            ->rule('password', 'not_empty')
            ->rule('g-recaptcha-response', 'not_empty');

        if (!$validator->check()) {
            throw $this->createUserCreateException(
                [
                    'error'   => true,
                    'message' => array_values($validator->errors('')),
                ]
            );
        }

        if (! $this->recaptcha->check($data['g-recaptcha-response'])) {
            throw $this->createUserCreateException(
                [
                    'error'   => true,
                    'message' => [Kohana::message('user', 'captchaErr')],
                ]
            );
        }

        /** @var Users $userAlreadyExist */
        $userAlreadyExist = (new Users)
            ->where('email', '=', $data['email'])
            ->find();

        if (null !== $userAlreadyExist->getId()) {
            throw $this->createUserCreateException(
                [
                    'error'   => true,
                    'message' => [Kohana::message('user', 'emailErr')],
                ]
            );
        }
    }

    /**
     * @param array $messages
     * @return UserCreateException
     */
    private function createUserCreateException(array $messages)
    {
        $exception = new UserCreateException('An error occupied during create user');
        $exception->setValidateMessages($messages);
        return $exception;
    }
}
