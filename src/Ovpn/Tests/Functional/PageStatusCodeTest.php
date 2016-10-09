<?php

namespace Ovpn\Tests\Functional;

use Ovpn\TestFramework\WebTestCase;

class PageStatusCodeTest extends WebTestCase
{
    /**
     * @dataProvider getNotExistPages
     */
    public function testNotFoundPage($url)
    {
        $response = $this->request('GET', $url);
        $this->assertStatusCode($response, 404);
    }

    /**
     * @dataProvider getExistPages
     */
    public function testOkPage($url)
    {
        $response = $this->request('GET', $url);
        $this->assertStatusCode($response, 200);
    }

    /**
     * @dataProvider getAccessDeniedPages
     */
    public function testAccessDeniedPage()
    {
        $response = $this->request('GET', '/profile');
        $this->assertRedirectResponse($response, '/');
    }

    public function getNotExistPages()
    {
        return [
            ['/test/dee'],
            ['/were'],
            ['/index.php'],
            ['/user/verify/notFoundException']
        ];
    }

    public function getExistPages()
    {
        return [
            ['/'],
            ['/faq'],
            ['/ajax/api'],
            ['/ajax/checkconnection'],
            ['/guide'],
            ['/proxy'],
            ['/user/login'],
            ['/user/create'],
            ['/user/newpasswordrequest'],
            ['/user/setnewpassword']
        ];
    }

    public function getAccessDeniedPages()
    {
        return [
            ['/profile'],
            ['/profile/create'],
            ['/profile/getinfovpn'],
            ['/profile/billing']
        ];
    }
}
