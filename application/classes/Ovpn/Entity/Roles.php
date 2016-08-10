<?php
namespace Ovpn\Entity;


class Roles extends \ORM
{

    protected $_table_name = 'roles';

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($text)
    {
        $this->description = $text;
        return $this;
    }

    public function getTrafficLimit()
    {
        return $this->traffic_limit;
    }

    public function setTrafficLimit($limit)
    {
        $this->traffic_limit = $limit;
        return $this;
    }

    public function getDayCost()
    {
        return $this->day_cost;
    }

    public function setDayCost($cost)
    {
        $this->day_cost = $cost;
        return $this;
    }

    public function getHostsLimit()
    {
        return $this->hosts_limit;
    }

    public function setHostsLimit($count)
    {
        $this->hosts_limit = $count;
        return $this;
    }

    public function getMinBalance()
    {
        return $this->min_balance;
    }

    public function setMinBalance($amount)
    {
        $this->min_balance = $amount;
        return $this;
    }

    public function getRoleName()
    {
        return $this->role_name;
    }

    public function setRoleName($name)
    {
        $this->role_name  = $name;
        return $this;
    }

}