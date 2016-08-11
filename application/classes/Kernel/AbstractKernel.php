<?php

namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractKernel
{
    /**
     * @var AbstractBundle[]
     */
    protected static $bundles;

    /**
     * @var ContainerBuilder
     */
    protected static $container;

    /**
     * @param array $bundles
     * @throws \Exception
     */
    public static function registrationBundle(array $bundles)
    {
        if (static::$bundles) {
            throw new \Exception('Bundles are already registered');
        }

        static::$bundles = $bundles;
    }

    /**
     * @return AbstractBundle[]
     */
    public static function getBundles()
    {
        return Kernel::$bundles;
    }

    /**
     * @return ContainerBuilder
     */
    public static function getContainer()
    {
        if (! Kernel::$container) {
            $kernel = new Kernel();
            Kernel::$container = $kernel->getContainerBuilder();
        }

        return Kernel::$container;
    }

    /**
     * @return ContainerBuilder
     */
    abstract public function getContainerBuilder();
}