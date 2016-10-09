<?php

namespace Ovpn\Tests\Functional;

use Ovpn\Entity\Roles;
use Ovpn\Entity\Users;
use Ovpn\TestFramework\WebTestCase;
use Ovpn\Entity\UsersInterface;

/**
 * @dbIsolation
 */
class ResetPasswordTest extends WebTestCase
{

    /**
     * Token for reset password
     *
     * @var string
     */
    protected static $token = 'secretToken';

    protected static $email = 'test1@okvpn.org';

    /**
     * @var UsersInterface
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $user = self::$client->getContainer()->get('ovpn_user.repository')
            ->findUserByEmail('test1@okvpn.org');

        if (null === $user) {
            $user = self::createUser(self::$email);
        }

        $user->setToken(self::$token);
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->user = $this->getClient()->getContainer()->get('ovpn_user.repository')
            ->findUserByToken(self::$token);
    }

    public function testEmailNotFound()
    {
        $this->request('POST', '/user/newpasswordrequest');
        $result = $this->getJsonResponse();
        $this->assertEquals($result,
            [
                'message' => 'Пользователь с такими данными не зарегистрирован',
                'error' => true,
            ]
        );
    }
    
    public function testUserFoundByToken()
    {
        $this->assertInstanceOf('Ovpn\Entity\Users', $this->user);
    }

    /**
     * @dataProvider newPasswordDataProvider
     * @depends testUserFoundByToken
     *
     * @param array $param
     * @param string $message
     * @param bool $error
     */
    public function testSetNewPassword(array $param, $message, $error)
    {
        $param['token'] = self::$token;

        $oldPassword = $this->user->getPassword();

        $this->request('POST', '/user/setnewpassword/', $param);
        $response = $this->getJsonResponse();
        $this->assertEquals($response,
            [
                'error'   => $error,
                'message' => $message
            ]
        );

        $newPassword = $this->getClient()->getContainer()->get('ovpn_user.repository')
            ->findUserByEmail(self::$email)->getPassword();
        $this->assertSame($error, $oldPassword == $newPassword);
    }

    public function testTokenNotFoundAfterResetPassword()
    {
        $this->assertNull($this->user);
    }

    public function newPasswordDataProvider()
    {
        return [
            'smallPassword' => [
                'param' => [
                    'password' => '12345',
                    'confirm'  => '12345'
                ],
                'message' => ['Поле пароль не должно быть короче 6 символов'],
                'error'   => true
            ],
            'passwordNotMatches' => [
                'param' => [
                    'password' => '123456',
                    'confirm'  => '12345'
                ],
                'message' => ['Пароли не совпадают'],
                'error'   => true
            ],
            'passwordReset' => [
                'param' => [
                    'password' => '12345678',
                    'confirm'  => '12345678'
                ],
                'message' => '',
                'error'   => false
            ]
        ];
    }

    /**
     * @param $email
     * @return Users
     */
    protected static function createUser($email)
    {
        $user = new Users();
        $user
            ->setEmail($email)
            ->setChecked(true)
            ->setPassword('pass')
            ->setRole(new Roles(1));

        $user->save();
        return $user;
    }
}
