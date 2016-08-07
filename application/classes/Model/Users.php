<?php

/**
 * Created by PhpStorm.
 * User: jurasikt
 * Date: 6/12/16
 * Time: 12:57 AM
 */
class Model_Users extends ORM implements Model_UsersIntrface
{
    protected $_table_name = 'users';

    protected $_foreign_key_suffix = '';

    protected $_belongs_to = array(
        'roles' => array(
            'model'       => 'Roles',
            'foreign_key' => 'role',
        ),
    );

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->pass;
    }

    public function setPassword($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    public function setDate($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d H:i:s');
        }
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setLastLogin($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d H:i:s');
        }
        $this->last_login = $date;
        return $this;
    }

    public function getLastLogin()
    {
        return $this->last_login;
    }

    public function setChecked($flag)
    {
        $this->checked = (bool) $flag;
        return $this;
    }

    public function getChecked()
    {
        $checked = $this->checked;
        // fix it in orm
        if (is_bool($checked)) {
            return $checked;
        } elseif ($checked == 't') {
            return true;
        }
        return false;
    }

    public function setRole(Model_Roles $role)
    {
        $this->roles = $role;
        return $this;
    }

    public function getRole()
    {
        return $this->roles;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    public function getToken()
    {
        return $this->token;
    }

}