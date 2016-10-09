<?php

namespace Ovpn\Entity;

class VpnUser extends \ORM
{
    protected $_table_name = 'vpn_user'; // @codingStandardsIgnoreLine

    protected $_foreign_key_suffix = ''; // @codingStandardsIgnoreLine

    protected $_belongs_to = array( // @codingStandardsIgnoreLine
        'host' => array(
            'model'       => 'Ovpn:Entity:Host',
            'foreign_key' => 'vpn_id',
        ),
        'user' => array(
            'model'       => 'Ovpn:Entity:Users',
            'foreign_key' => 'user_id',
        ),
    );

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDateCreate($date_create = null)
    {
        if (null === $date_create) {
            $date_create = date('Y-m-d H:i:s');
        }
        
        $this->date_create = $date_create;
        return $this;
    }

    public function getDateCreate()
    {
        return $this->date_create;
    }

    public function getDateDelete()
    {
        return $this->date_delete;
    }

    public function setDateDelete($date_delete = null)
    {
        if (null === $date_delete) {
            $date_delete = date('Y-m-d H:i:s');
        }
        
        $this->date_delete = $date_delete;
        return $this;
    }


    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }
}
