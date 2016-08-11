<?php
namespace Entity;

class Host extends \ORM
{
    protected $_table_name = 'vpn_hosts';

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

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }


    public function getPlaces()
    {
        return $this->free_places;
    }

    public function setPlaces($places)
    {
        $this->free_places = $places;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getOrdernum()
    {
        return $this->ordernum;
    }

    public function setOrdernum($ordernum)
    {
        $this->ordernum = $ordernum;
        return $this;
    }

    public function getEnable()
    {
        return $this->enable;
    }

    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    public function getSpeedtest()
    {
        return $this->speedtest;
    }

    public function setSpeedtest($speedtest)
    {
        $this->speedtest = $speedtest;
        return $this;
    }
}