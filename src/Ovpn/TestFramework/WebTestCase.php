<?php

namespace Ovpn\TestFramework;

use Database;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{

    const DB_ISOLATION_ANNOTATION = 'dbIsolation';

    /**
     * @var Client
     */
    protected static $client;

    protected static $dbIsolation;

    /**
     * @var \Response
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        static::$client = new Client();

        if (static::isDbIsolation()) {
            Database::instance()->begin();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (static::isDbIsolation()) {
            Database::instance()->rollback();
        }
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
     *
     * @return \Response
     */
    public function request(
        $method,
        $url,
        array $parameters = [],
        array $applicationData = [],
        array $cookie = []
    ) {
        return $this->response = $this->getClient()
            ->prepareClient($method, $url, $parameters, $applicationData, $cookie)
            ->getRequest()
            ->execute();
    }
    
    public function getJsonResponse()
    {
        $response = $this->response;
        
        $this->assertJsonResponse($response);
        $this->assertTrue((bool)json_decode($response->body()));
        return json_decode($response->body(), true);
    }
    
    /**
     * @param \Response $response
     */
    public function assertJsonResponse(\Response $response)
    {
        $contentType = $response->headers('Content-type');
        $this->assertSame('application/json', $contentType);
    }

    /**
     * todo: add throw PHPUnit_Framework_ExpectationFailedException in 2.2
     * @param \Response $response
     * @param $code
     */
    public function assertStatusCode(\Response $response, $code)
    {
        $this->assertSame($response->status(), $code);
    }

    /**
     * todo: add throw PHPUnit_Framework_ExpectationFailedException in 2.2
     * @param \Response $response
     * @param $url
     */
    public function assertRedirectResponse(\Response $response, $url)
    {
        $this->assertSame($response->headers('location'), $url);
    }
    
    /**
     * @return bool
     */
    private static function isDbIsolation()
    {
        $calledClass = get_called_class();
        if (! isset(self::$dbIsolation[$calledClass])) {
            self::$dbIsolation[$calledClass] = self::isClassHasAnnotation($calledClass, self::DB_ISOLATION_ANNOTATION);
        }

        return self::$dbIsolation[$calledClass];
    }

    /**
     * @param string $className
     * @param string $annotationName
     *
     * @return bool
     */
    private static function isClassHasAnnotation($className, $annotationName)
    {
        $annotations = \PHPUnit_Util_Test::parseTestMethodAnnotations($className);
        return isset($annotations['class'][$annotationName]);
    }
}
