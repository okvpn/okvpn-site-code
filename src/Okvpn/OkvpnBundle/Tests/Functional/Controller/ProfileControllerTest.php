<?php

namespace Okvpn\OkvpnBundle\Tests\Functional\Controller;

use Okvpn\KohanaProxy\ORM;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;

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
        $response = $this->request('GET', '/profile/vpncreate');
        $this->assertStatusCode($response, 200);
        $this->assertContains('pa1', $response->body());
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
}
