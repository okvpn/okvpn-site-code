<?php

namespace Ovpn\TestFramework;

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
    const URL = 'http://test.loc/';

    protected static $seleniumHost = '127.0.0.1';
    protected static $seleniumPort = '4444';
    protected static $seleniumBrowser = 'phantomjs';
    protected static $seleniumTestUrl = 'http://test.loc/';


    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setHost(static::$seleniumHost);
        $this->setPort(intval(static::$seleniumPort));
        $this->setBrowser(static::$seleniumBrowser);
        $this->setBrowserUrl(static::$seleniumTestUrl);

        //added for xhprof tracing and works only with phantomjs
        $this->setDesiredCapabilities(
            array('phantomjs.page.customHeaders.PHPUNIT-SELENIUM-TEST-ID' => $this->getTestId())
        );
    }

    /**
     * @inheritdoc
     */
    public function url($url)
    {
        parent::url(static::$seleniumTestUrl . $url);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

}