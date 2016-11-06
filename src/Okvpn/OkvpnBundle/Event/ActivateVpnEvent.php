<?php

namespace Okvpn\OkvpnBundle\Event;

use Okvpn\OkvpnBundle\Entity\Users;
use Symfony\Component\EventDispatcher\Event;

class ActivateVpnEvent extends Event
{
    /** @var  Users */
    public $user;
    
    /** @var  int */
    public $host;
    
    private $data;
    
    public function __construct(Users $user, $host)
    {
        $this->user = $user;
        $this->host = $host;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
