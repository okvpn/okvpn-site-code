<?php

namespace Okvpn\OkvpnBundle\Event;

use Okvpn\OkvpnBundle\Entity\Users;
use Symfony\Component\EventDispatcher\Event;

class CreateUserEvent extends Event
{
    /** @var Users  */
    protected $user;

    /** @var  array */
    private $data;

    public function __construct(Users $user)
    {
        $this->user = $user;
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(Users $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
