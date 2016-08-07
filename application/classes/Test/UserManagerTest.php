<?php

class UsersTest extends PHPUnit_Framework_TestCase
{
    private $_user;

    private $_userManager;

    private $_uid = 10;

    public function setUp()
    {
        $this->_user = new Model_Users($this->_uid);
        $this->_userManager = new Model_UserManager();
    }

    public function testSetUser()
    {
        $um = $this->getUserManager();
        
    }

    private function getUser()
    {
        return $this->_user;
    }

    private function getUserManager()
    {
        return $this->_userManager;
    }
}