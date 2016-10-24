<?php

namespace Okvpn\OkvpnBundle\Filter;

use Okvpn\KohanaProxy\Kohana;
use Okvpn\OkvpnBundle\Entity\UsersInterface;
use Okvpn\OkvpnBundle\Repository\UserRepository;

class UserFilter
{

    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}
