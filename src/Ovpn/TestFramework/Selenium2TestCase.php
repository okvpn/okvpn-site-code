<?php

namespace Ovpn\TestFramework;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Ovpn\Tests\Selenium\Page\Login;

/**
 * Class Selenium2TestCase
 *
 * Basic Usage. Run tests:
 *
 * java -jar selenium-server-standalone-***.jar -role hub
 * phantomjs --webdriver=8080 --webdriver-selenium-grid-hub=http://127.0.0.1:4444
 *
 * @package Ovpn\TestFramework
 */
abstract class Selenium2TestCase extends \PHPUnit_Extensions_Selenium2TestCase
{

    protected $seleniumHost = '127.0.0.1';
    protected $seleniumPort = '4444';
    protected $seleniumBrowser = 'phantomjs';
    protected $seleniumTestUrl = 'http://test.loc/';

    protected static $hostHub;

    /**
     * @var RemoteWebDriver
     */
    protected static $driver;

    /**
     * @var int milliseconds
     */
    protected static $timeout = 5000;


    static public function setUpBeforeClass()
    {
        $host = static::$hostHub;

        if ($host) {
            static::$driver = RemoteWebDriver::create($host, DesiredCapabilities::phantomjs());
        }
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setHost($this->seleniumHost);
        $this->setPort(intval($this->seleniumPort));
        $this->setBrowser($this->seleniumBrowser);
        $this->setBrowserUrl($this->seleniumTestUrl);

        $this->setDesiredCapabilities(
            ['phantomjs.page.customHeaders.PHPUNIT-SELENIUM-TEST-ID' => $this->getTestId()]
        );
    }

    /**
     * @inheritdoc
     */
    public function url($url = null)
    {
        $payload = ($url !== null) ? $this->seleniumTestUrl . $url : null;
        return parent::url($payload);
    }

    /**
     * @return RemoteWebDriver
     */
    public function getDriver()
    {
        return static::$driver;
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->cookie()->clear();
        parent::tearDown();
    }

    public function ignorePageError()
    {
        $this->execute([
            'script' => $this->getJsCodeErrorIgnore(),
            'args' => []
        ]);

    }

    public function waitToAjax()
    {
        $jsCode = $this->getJsCodeJQueryIsActive();

        $this->waitUntil(
            function () use ($jsCode) {
                $status = $this->execute([
                    'script' => $jsCode,
                    'args' => [],
                ]);

                return $status ? true : null;
            }, static::$timeout
        );
    }

    /**
     * @return string
     */
    public function getSeleniumUrl()
    {
        return $this->seleniumTestUrl;
    }

    public function waitToRedirect($page)
    {
        $this->waitUntil(
            function () use ($page) {
                return ($this->seleniumTestUrl . $page == $this->url()) ? true : null;
            }, static::$timeout
        );
    }

    /**
     * @param null $username
     * @param null $password
     * @return AbstractPage
     */
    public function login($username = null, $password = null)
    {
        $username = $username ?? 'tsykun314@gmail.com';
        $password = $password ?? 'php123456';

        $login = new Login($this);
        $login->login()
            ->setUsername($username)
            ->setPassword($password)
            ->submit();

        $this->waitToRedirect('profile');
        return $login;
    }

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function waitToElementEnable($element)
    {
        $this->waitUntil(
            function () use ($element) {

                return $element->displayed() ? true : null;
            }, static::$timeout
        );
    }

    private function getJsCodeErrorIgnore()
    {
        return <<<JS
            if (!window.onerror) {
                window.onerror = function(errorMsg, url, lineNumber, column, errorObj) {
                    return false;
                }
            }
JS;
    }

    private function getJsCodeJQueryIsActive()
    {
        return <<<JS
            return jQuery && jQuery.active == 0;
JS;
    }

}