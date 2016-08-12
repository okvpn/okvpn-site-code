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
     * @var int milliseconds
     */
    protected static $timeout = 5000;


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

    protected function ignorePageError()
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
        sleep(1);
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