<?php
namespace Ovpn\TestFramework;

/**
 * Class AbstractPage
 */
abstract class AbstractPage implements PageInterface
{
    /**
     * @var Selenium2TestCase
     */
    protected $test;

    /**
     * @param $testCase
     *
     */
    public function __construct($testCase)
    {
        $this->test = $testCase;
        $this->test->url(static::URL);
        $this->test->ignorePageError();
        $this->test->waitToAjax();
    }

    /**
     * @return Selenium2TestCase
     */
    public function getTest()
    {
        return $this->test;
    }
}
