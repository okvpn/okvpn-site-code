<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\OkvpnBundle\Repository\UserRepository;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;
use Okvpn\TestFrameworkBundle\Mock\MockMailer;

/**
 * @keepCookie
 * @dbIsolation
 */
class UserControllerTest extends WebTestCase
{

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->userRepo = $this->getClient()->getContainer()->get('ovpn_user.repository');
    }

    public function testLoginWhenUserNotExist()
    {
        $this->request(
            'POST',
            '/user/login',
            [
                'email' => 'not_exist',
                'password' => self::USER_PASSWORD
            ]
        );

        $response = $this->getJsonResponse();
        $this->assertArraySubset(['error' => true], $response);
    }

    /**
     * @dataProvider userDataProvider
     *
     * @param $data
     * @param $result
     */
    public function testSignWhenDataNotValid($data, $result)
    {
        $response = $this->request(
            'POST',
            '/user/create',
            $data
        );

        $this->assertStatusCode($response, 200);
        $response = $this->getJsonResponse();

        $this->assertSame(true, $response['error']);
        $this->assertArraySubset([$result], $response['message']);
    }
    
    public function testCreate()
    {
        $data = [
            'g-recaptcha-response' => 'not_empty',
            'password' => '123456',
            'email' => 'test@example.com'
        ];

        /** @var MockMailer $mockMailer */
        $mockMailer = $this->getClient()->getContainer()->get('ovpn_mailer');

        $this->request(
            'POST',
            '/user/create',
            $data
        );
        $response = $this->getJsonResponse();

        $this->assertSame(false, $response['error']);
        $this->assertInstanceOf(
            'Okvpn\OkvpnBundle\Entity\UsersInterface',
            $this->userRepo->findUserByEmail('test@example.com')
        );
        /** @var \Swift_Message $mail */
        $mail = $mockMailer->getLastInvokeValue('send');
        $message = (string) $mail->getBody();
        $this->assertRegExp('/user\/verify\/(\w+)/', $message);

        preg_match_all('/user\/verify\/(\w+)/', $message, $result);
        return $result[1][0];
    }

    /**
     * @depends testCreate
     */
    public function testLoginWhenUserNotApproveEmail()
    {
        $this->request(
            'POST',
            '/user/login',
            [
                'email' => 'test@example.com',
                'password' => '123456'
            ]
        );

        $response = $this->getJsonResponse();
        $this->assertArraySubset(['error' => true], $response);
    }

    /**
     * @depends testCreate
     *
     * @param $token
     */
    public function testConfirmEmail($token)
    {
        $user = $this->userRepo->findUserByEmail('test@example.com', true);
        $this->assertNull($user);

        $this->request('GET', "user/verify/$token");

        $this->assertInstanceOf(
            'Okvpn\OkvpnBundle\Entity\UsersInterface',
            $this->userRepo->findUserByEmail('test@example.com')
        );
    }

    /**
     * @depends testConfirmEmail
     *
     * @param $token
     */
    public function testRepeatConfirmEmail($token)
    {
        $response = $this->request('GET', "user/verify/$token");
        $this->assertStatusCode($response, 404);
    }


    /**
     * @depends testConfirmEmail
     */
    public function testLogin()
    {
        $this->request(
            'POST',
            '/user/login',
            [
                'email' => 'test@example.com',
                'password' => '123456'
            ]
        );

        $this->assertNotNull(
            $this->getClient()->getContainer()->get('ovpn_security')->getUser()
        );
    }

    /**
     * @depends testLogin
     */
    public function testLogout()
    {
        $response = $this->request('GET', '/user/logout');
        $this->assertStatusCode($response, 302);
        $this->assertRedirectResponse($response, '');

        $this->assertNull(
            $this->get('ovpn_security')->getUser()
        );
    }

    public function userDataProvider()
    {
        return [
            [
                'payload' => [
                    'g-recaptcha-response' => 'not_empty',
                    'password' => '123',
                    'email' => 'test@example.com'
                ],
                'result' => 'Поле пароль не должно быть короче 6 символов',
            ],
            [
                'payload' => [
                    'g-recaptcha-response' => 'not_empty',
                    'password' => '123456',
                    'email' => 'test1.ci@okvpn.org'
                ],
                'result' => 'Пользователь с таким емайлом был зарегистрирован ранее',
            ],
            [
                'payload' => [
                    'g-recaptcha-response' => 'not_empty',
                    'password' => '123456',
                    'email' => 'test1.ci@okvpn'
                ],
                'result' => 'Поле емайл должно быть адресом электронной почты',
            ]
        ];
    }
}
