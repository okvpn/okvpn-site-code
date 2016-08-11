<?php

namespace Ovpn\TestFramework;


abstract class Selenium2TestCase extends \PHPUnit_Extensions_Selenium2TestCase
{
    const URL = '/';

    protected static $seleniumHost = '127.0.0.1';
    protected static $seleniumPort = '4444';
    protected static $seleniumBrowser = 'phantomjs';
    protected static $seleniumTestUrl = 'http://loc.okvpn.org';


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

    public function setUpPage()
    {
        $this->url(static::URL);
        // @codingStandardsIgnoreStart
        $this->currentWindow()->size(array('width' => 1920, 'height' => 1080));
        // @codingStandardsIgnoreEnd
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}