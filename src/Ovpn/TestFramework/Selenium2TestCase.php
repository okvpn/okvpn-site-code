<?php

namespace Ovpn\TestFramework;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * Class Selenium2TestCase
 *
 * Basic Usage. Run tests:
 *
 * java -jar selenium-server-standalone-2.44.0.jar -role hub
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

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var int milliseconds
     */
    protected static $timeout = 5000;

    /*public function __construct($name, array $data, $dataName)
    {
        $a =
        parent::__construct($name, $data, $dataName);
    }*/

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $host = 'http://localhost:4444/wd/hub';

        $this->setHost($this->seleniumHost);
        $this->setPort($this->seleniumPort);
        $this->setBrowser($this->seleniumBrowser);
        $this->setBrowserUrl($this->seleniumTestUrl);
        $this->driver =  RemoteWebDriver::create($host, DesiredCapabilities::phantomjs());

        //added for xhprof tracing and works only with phantomjs
        $this->setDesiredCapabilities(
            ['phantomjs.page.customHeaders.PHPUNIT-SELENIUM-TEST-ID' => $this->getTestId()]
        );
    }

    /**
     * @inheritdoc
     */
    public function url($url)
    {
        parent::url($this->seleniumTestUrl . $url);
    }

    /**
     * @return RemoteWebDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
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
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function waitToElementEnable($element)
    {

        $this->waitUntil(
            function () use ($element) {

                return $element->enabled() ? true : null;
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