<?php

namespace Okvpn\OkvpnBundle\TestFramework;

use Okvpn\KohanaProxy\Database;
use Okvpn\KohanaProxy\URL;
use Symfony\Component\DependencyInjection\Container;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{

    const DB_ISOLATION_ANNOTATION = 'dbIsolation';

    const COOKIE_ENABLE_ANNOTATION = 'keepCookie';
    
    const USER_NAME = 'test1.ci@okvpn.org';
    const USER_PASSWORD = '123456';

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var array
     */
    protected static $cookie = [];

    protected static $annotation = [];

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

        if (static::isClassHasAnnotation(self::DB_ISOLATION_ANNOTATION)) {
            Database::instance()->begin();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        if (static::isClassHasAnnotation(self::DB_ISOLATION_ANNOTATION)) {
            Database::instance()->rollback();
        }
        
        self::clearCookie();
        self::clearSession();
        self::$annotation = [];
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function loginClient(
        $username = self::USER_NAME,
        $password = self::USER_PASSWORD
    ) {
        try {
            $this->getClient()->clientBasicAuthentication($username, $password);
        } catch (\Exception $e) {
            $this->markTestIncomplete(
                $e->getMessage()
            );
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
        $this->response = $this->getClient()
            ->prepareClient(
                $method,
                $url,
                $parameters,
                $applicationData,
                array_merge(self::$cookie, $cookie)
            )
            ->getRequest()->execute();
        
        if (static::isClassHasAnnotation(self::COOKIE_ENABLE_ANNOTATION)) {
            $this->setCookieFromXDebugHeaders();
        }
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function get($service, $invalidBehavior = Container::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return $this->getClient()->getContainer()->get($service, $invalidBehavior);
    }

    /**
     * todo: move to VariableContext class 2.2
     * Create and save cookie from headers
     */
    protected function setCookieFromXDebugHeaders()
    {
        try {
            $headers = $this->getXDebugHeaders();
        } catch (\Exception $e) {
            $this->markTestSkipped(
                $e->getMessage()
            );
            return;
        }
        //todo remove this
        foreach ($headers as $header) {
            if (preg_match('/Set-Cookie:/', $header)) {
                $header = preg_replace('/Set-Cookie:/', '', $header);
                $header = explode(';', $header);
                $cookie = explode('=', reset($header));
                self::$cookie[trim($cookie[0])] = trim($cookie[1]);
            }
        }
    }

    public static function clearCookie()
    {
        self::$cookie = [];
        $_COOKIE = [];
    }
    
    public static function clearSession()
    {
        \Session::instance()->destroy();
        $_SESSION = [];
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
        $this->assertThat(
            $response->headers('location'),
            $this->logicalOr(
                $this->equalTo($url),
                $this->equalTo(URL::base(true) . $url)
            )
        );
    }

    /**
     * @param $annotationName
     * @return bool
     */
    private static function isClassHasAnnotation($annotationName)
    {
        $calledClass = get_called_class();

        if (isset(self::$annotation[$annotationName])) {
            return self::$annotation[$annotationName];
        }
        
        $annotations = \PHPUnit_Util_Test::parseTestMethodAnnotations($calledClass);
        self::$annotation[$annotationName] = isset($annotations['class'][$annotationName]);
        return self::$annotation[$annotationName];
    }

    //todo: move to VariableContext class 2.2
    /**
     * @return array
     */
    private function getXDebugHeaders()
    {
        if (!function_exists('xdebug_get_headers')) {
            throw new \RuntimeException('Xdebug requeid for this tests');
        }
        return xdebug_get_headers();
    }
}
