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

    /**
     * @return bool
     */
    public function checkCurrentUrl()
    {
        return $this->test->getSeleniumUrl() . static::URL == $this->test->url();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/open(.+)/i', $name, $result)) {

            if (isset($arguments[0]) && ! empty($arguments[0])) {
                //$arguments[0] the part of namespace class
                $namespace = $arguments[0] . '\\Tests\\Selenium';
                unset($arguments[0]);
                $arguments = array_values($arguments);
            } else {
                $namespace = preg_replace('/\\\\w+$/i', '', get_class($this->test));
            }

            $class = $namespace . '\\Page\\' . $result[1];
            $class = new \ReflectionClass($class);
            return $class->newInstanceArgs(array_merge([$this->test], $arguments));
        }

        return null;
    }
}
