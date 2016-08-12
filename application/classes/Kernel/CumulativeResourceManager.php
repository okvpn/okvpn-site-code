<?php

namespace Kernel;

use Symfony\Component\DependencyInjection\Container;

class CumulativeResourceManager
{
    /**
     * @var CumulativeResourceManager
     */
    private static $instance = null;

    /**
     * @var Container
     */
    private $container;

    /**
     * @return CumulativeResourceManager
     */
    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Container $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    private function __construct()
    {
    }

    public function __clone()
    {
        throw new \Exception('CumulativeResourceManager can not be cloned');
    }

    public function __sleep()
    {
        throw new \Exception('CumulativeResourceManager can not be serialize');
    }

    public function __wakeup()
    {
        throw new \Exception('CumulativeResourceManager can not be unserialize');
    }


}