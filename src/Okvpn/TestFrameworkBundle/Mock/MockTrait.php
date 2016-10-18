<?php

namespace Okvpn\TestFrameworkBundle\Mock;

trait MockTrait
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected static $mockObject;

    /**
     * @var array
     */
    protected static $enableMethods = [];

    /**
     * @var array
     */
    protected static $invokeHistory;

    /**
     * @param $mockObject
     */
    public static function setMockBuilder($mockObject)
    {
        self::$mockObject = $mockObject;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public static function getMock()
    {
        return self::$mockObject;
    }

    /**
     * @param $name
     */
    public static function enableOriginMethod($name)
    {
        self::$enableMethods[] = $name;
    }

    /**
     * @param $name
     * @return bool
     */
    protected static function isEnableParentMethod($name)
    {
        return in_array($name, self::$enableMethods);
    }

    /**
     * @param $method
     * @param $value
     */
    public static function saveInvokeValue($method, $value)
    {
        self::$invokeHistory[$method][] = $value;
    }

    /**
     * @param $method
     * @return mixed
     */
    public static function getLastInvokeValue($method)
    {
        return end(self::$invokeHistory[$method]);
    }
}
