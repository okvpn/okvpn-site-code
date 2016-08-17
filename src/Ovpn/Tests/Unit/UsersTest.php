<?php

/**
 *
 * @deprecated
 */
class UsersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Model_Users
     */
    private $user;

    private $_uid = 10;

    public function setUp()
    {
        $this->user = new Model_Users($this->_uid);
    }

    public function testRole()
    {
        $role = $this->user->getRole();
        $this->assertInstanceOf('Model_Roles', $role);
    }

    public function testEmail()
    {
        $email = Text::random('alnum', 16) . '@test.com';
        $this->user->setEmail($email)
            ->save();

        $user = new Model_Users($this->_uid);

        $this->assertSame($email, $user->getEmail());
    }

    public function testPassword()
    {
        $password = Text::random('alnum', 16);
        $this->user->setPassword($password)
            ->save();

        $user = new Model_Users($this->_uid);
        $this->assertSame(true, password_verify($password, $user->getPassword()));
    }

    public function testDate()
    {
        $date = date('Y-m-d H:i:s');
        $this->user->setDate($date)
            ->save();

        $user = new Model_Users($this->_uid);
        $this->assertSame($date, $user->getDate());
    }

    public function testLastLogin()
    {
        $date = date("Y-m-d H:i:s");
        $this->user->setLastLogin($date)
            ->save();

        $user = new Model_Users(($this->_uid));
        $this->assertSame($date, $user->getLastLogin());
    }

    public function testChecked()
    {
        $this->assertSame(true, $this->user->getChecked());
    }

    public function testToken()
    {
        $token = Text::random('alnum', 16);
        $this->user->setToken($token)
            ->save();

        $user = new Model_Users($this->_uid);
        $this->assertSame($token, $user->getToken());
    }

}