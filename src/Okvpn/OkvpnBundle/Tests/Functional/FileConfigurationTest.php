<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;

class FileConfigurationTest extends WebTestCase
{
    /** @var  Config */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->config = $this->get('ovpn_config');
    }

    /**
     * @dataProvider configParamProvider
     *
     * @param string $param
     * @param string|null $value
     */
    public function testExistRequiredParam($param, $value = null)
    {
        $result = $this->config->get($param);
        if (null === $value) {
            $this->assertNotNull($result);
            return;
        }
        $this->assertSame($result, $value);
    }

    public function configParamProvider()
    {
        return [
            [
                'param' => 'captcha:secret',
            ],
            [
                'param' => 'captcha:sitekey',
            ],
            [
                'param' => 'captcha:check',
                'value' => true
            ],
            [
                'param' => 'captcha:secret',
            ],
            [
                'param' => 'mailer:username',
            ],
            [
                'param' => 'mailer:password',
            ],
            [
                'param' => 'database:default:connection:hostname',
                'value' => '127.0.0.1'
            ],
            [
                'param' => 'database:default:connection:username'
            ],
            [
                'param' => 'database:default:connection:password'
            ],
            [
                'param' => 'database:default:connection:database'
            ],
            [
                'param' => 'vpn_domain'
            ]
        ];
    }
}
