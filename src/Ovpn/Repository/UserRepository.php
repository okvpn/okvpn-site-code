<?php

namespace Ovpn\Repository;

use Ovpn\Entity\Users;

class UserRepository
{
    /**
     * @param string $email
     * @return null|Users
     * @throws \Kohana_Exception
     */
    public function findUserByEmail(string $email)
    {
        /** @var Users $user */
        $user = (new Users)
            ->where('email', '=', $email)
            ->find();
        
        return (null !== $user->getId()) ? $user : null; 
    }

}