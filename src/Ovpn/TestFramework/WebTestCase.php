<?php

namespace Ovpn\TestFramework;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{

    const DB_ISOLATION_ANNOTATION = 'dbIsolation';

    /**
     * @var Client
     */
    protected static $client;

    protected static $dbIsolation;

    /**
     * @var
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        static::$client = new Client();

        if (static::isDbIsolation()) {
            \Database::instance()->begin();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (static::isDbIsolation()) {
            \Database::instance()->rollback();
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
     */
    public function request(
        $method,
        $url,
        array $parameters = [],
        array $applicationData = [],
        array $cookie = []
    ) {
        $this->response = $this->getClient()
            ->prepareClient($method, $url, $parameters, $applicationData, $cookie)
            ->getRequest()
            ->execute();
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
