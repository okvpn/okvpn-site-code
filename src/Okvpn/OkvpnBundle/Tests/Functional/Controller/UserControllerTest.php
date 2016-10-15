<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\OkvpnBundle\TestFramework\WebTestCase;

/**
 * @keepCookie
 */
class UserControllerTest extends WebTestCase
{

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

    public function testLogin()
    {
        $this->request(
            'POST',
            '/user/login',
            [
                'email' => self::USER_NAME,
                'password' => self::USER_PASSWORD
            ]
        );

        $response = $this->request('GET', '/profile');
        $this->assertStatusCode($response, 200);
        $this->assertRedirectResponse($response, null);
    }


}
