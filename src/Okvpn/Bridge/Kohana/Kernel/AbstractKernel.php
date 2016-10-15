<?php

namespace Okvpn\Bridge\Kohana\Kernel;

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
    public static function registrationBundles(array $bundles)
    {
        if (static::$bundles) {
            throw new \Exception('Bundles are already registered');
        }

        static::$bundles = $bundles;
    }
    
    public static function addBundle(AbstractBundle $bundle)
    {
        static::$bundles[] = $bundle;
    }

    /**
     * @return AbstractBundle[]
     */
    public static function getBundles()
    {
        return Kernel::$bundles;
    }

    /**
     * @deprecated
     *
     * @param $name
     * @return AbstractBundle
     */
    public static function getBundleByAlias($name)
    {
        foreach (self::$bundles as $bundleClass) {
            $reflect = new \ReflectionClass($bundleClass);
            if ($name == $reflect->getShortName()) {
                return $bundleClass;
            }
        }

        throw new \InvalidArgumentException(sprintf("Class %s not registration as bundle", $name));
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
    public function getContainerBuilder()
    {
        $container = new ContainerBuilder();
        $this->prepareContainer($container);

        $container->compile();

        return $container;
    }

    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    abstract public function prepareContainer(ContainerBuilder $container);
}
