<?php

namespace Ovpn\TestFramework;


abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var
     */
    protected $response;

    /**
     * todo add database isolation in 2.0
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        static::$client = new Client();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return static::$client;
    }

    /**
     * @param $method
     * @param $url
     * @param array $parameters
     * @param array $applicationData
     * @param array $cookie
     * @throws \Request_Exception
     */
    public function request(
        $method,
        $url,
        array $parameters = [],
        array $applicationData = [],
        array $cookie = []
    ) {
        $this->getClient()
            ->prepareClient($method, $url, $parameters, $applicationData, $cookie)
            ->getRequest()
            ->execute();
    }


}