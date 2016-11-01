<?php

namespace Okvpn\OkvpnBundle\Tests\Functional\Controller;

use Okvpn\OkvpnBundle\TestFramework\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * @dataProvider namedActionProvider
     *
     * @param $url
     * @param $contains
     */
    public function testAction($url, $contains)
    {
        $response = $this->request('GET', $url);
        $this->assertContains($contains, $response->body());
    }

    /**
     * @return array
     */
    public function namedActionProvider()
    {
        return [
            [
                'url' => '/faq',
                'contains' => 'OkVPN - FAQ11'
            ],
            [
                'url' => '/proxy',
                'contains' => 'OkVPN - Список прокси серверов'
            ],
            [
                'url' => '/guide',
                'contains' => 'OkVPN - Подключение'
            ]
        ];
    }
}
