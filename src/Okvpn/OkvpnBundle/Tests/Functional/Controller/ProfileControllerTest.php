<?php

namespace Okvpn\OkvpnBundle\Tests\Functional\Controller;

use Okvpn\KohanaProxy\ORM;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;
use Okvpn\TestFrameworkBundle\Mock\MockMailer;

/**
 * @dbIsolation
 * @keepCookie
 */
class ProfileControllerTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->loginClient();
    }

    public function testIndex()
    {
        $response = $this->request('GET', '/profile');
        $this->assertStatusCode($response, 200);
        $this->assertContains('OkVPN - Profile', $response->body());
    }
    
    public function testCreate()
    {
        $response = $this->request('GET', '/profile/viewvpn');
        $this->assertStatusCode($response, 200);
        $this->assertContains('pa1', $response->body());
    }

    /**
     * @dataProvider updateUserProvider
     *
     * @param $post
     * @param bool $error
     */
    public function testUpdate(array $post, $error)
    {
        $this->request('POST', 'profile/update', $post);
        $response = $this->getJsonResponse();
        $this->assertSame($response['error'], $error);
    }
    
    public function testInfoVpn()
    {
        $vpnHost = ORM::factory('OkvpnFramework:Host')->find_all()->current();

        $response = $this->request('GET', 'profile/getinfovpn/' . $vpnHost->getId());
        $this->assertStatusCode($response, 200);
        $this->assertContains('Действительная скортость', $response->body());
    }
    
    public function testBilling()
    {
        $response = $this->request('GET', '/profile/billing');
        $this->assertJsonResponse($response);
    }
    
    public function testSettings()
    {
        $response = $this->request('GET', 'profile/settings');
        $this->assertContains('OkVPN - settings', $response->body());
        $this->assertContains(
            $this->get('ovpn_security')->getUser()->getEmail(),
            $response->body()
        );
    }

    public function testActivateVpn()
    {
        $response = $this->request('POST', '/profile/activate/1');
        $this->assertStatusCode($response, 200);
        $response = $this->getJsonResponse();
        $this->assertSame(false, $response['error']);

        /** @var MockMailer $mailer */
        $mailer = $this->get('ovpn_mailer');
        /** @var \Swift_Message $message */
        $message = $mailer->getLastInvokeValue('send');

        $this->assertInstanceOf('Swift_Message', $message);
        /** @var \Swift_Attachment[] $attach */
        $attach = $message->getChildren();
        $this->assertCount(4, $attach);
        foreach ($attach as $item) {
            $this->assertInstanceOf('Swift_Attachment', $item);
            if ($item->getFilename() == 'client.key') {
                $this->assertContains('BEGIN PRIVATE KEY', $item->getBody());
            }
        }
    }

    public function updateUserProvider()
    {
        return [
            [
                'post' => [
                    'email' => 'not_valid',
                    'password' => '123456',
                    're_password' => '123456'
                ],
                'error' => true,
            ],
            [
                'post' => [
                    'email' => 'test@okvpn.org',
                    'password' => '1234',
                ],
                'error' => true,
            ],
            [
                'post' => [
                    'email' => 'test@okvpn.org',
                    're_password' => '123456'
                ],
                'error' => true,
            ],
            [
                'post' => [
                    'email' => 'test@okvpn.org',
                    'password' => '12345',
                    're_password' => '12345'
                ],
                'error' => true,
            ],
            [
                'post' => [
                    'email' => 'test@okvpn.org',
                    'password' => '123456',
                    're_password' => '123456'
                ],
                'error' => false,
            ],
        ];
    }
}
