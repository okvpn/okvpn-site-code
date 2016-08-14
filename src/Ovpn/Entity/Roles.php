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

    /**
     * @return array
     */
    public function getRolesName()
    {
        $roles = unserialize($this->role_name);
        if (!is_array($roles)) {
            throw new \RuntimeException('Invalid data in database. Field "role_name" must be unserializable');
        }
        return $roles;
    }

    /**
     * @param array $names
     * @return $this
     */
    public function setRolesName(array $names)
    {
        $this->role_name  = unserialize($names);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addRoleName($name)
    {
        $rolesName = $this->getRolesName();
        $this->setRolesName(array_merge($rolesName,[$name]));
        return $this;
    }

}