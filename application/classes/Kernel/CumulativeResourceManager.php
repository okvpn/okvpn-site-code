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
     * @var AbstractBundle[]
     */
    private $bundles;

    /**
     * @return CumulativeResourceManager
     */
    final public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
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
     * @param array $bundles
     * @return $this
     */
    public function setBundles(array $bundles)
    {
        $this->bundles = $bundles;
        return $this;
    }

    /**
     * @return Container
     * @throws \Exception
     */
    public function getContainer()
    {
        if (is_null($this->container)) {
            throw new \Exception('');
        }
        return $this->container;
    }

    /**
     * @return AbstractBundle[]
     * @throws \Exception
     */
    public function getBundles()
    {
        if (is_null($this->bundles)) {
            throw new \Exception('');
        }
        return $this->bundles;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
        throw new \Exception('CumulativeResourceManager can not be cloned');
    }

    final public function __sleep()
    {
        throw new \Exception('CumulativeResourceManager can not be serialize');
    }

    final public function __wakeup()
    {
        throw new \Exception('CumulativeResourceManager can not be unserialize');
    }


}