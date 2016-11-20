<?php

namespace Okvpn\Bridge\Kohana\Kernel;

use Okvpn\KohanaProxy\Kohana;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

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
        if ($this->isDebug() || !file_exists($this->getContainerCacheFileName())) {
            $container = new ContainerBuilder();
            $this->prepareContainer($container);
            $container->compile();

            if (!$this->isDebug()) {
                $dumper = new PhpDumper($container);
                file_put_contents(
                    $this->getContainerCacheFileName(),
                    $dumper->dump(['class' => $this->getContainerCacheClassName()])
                );
            }
        } else {
            require_once $this->getContainerCacheFileName();
            $class = $this->getContainerCacheClassName();
            $container = new $class();
        }

        return $container;
    }

    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    abstract public function prepareContainer(ContainerBuilder $container);

    /**
     * @return string
     */
    private function getContainerCacheFileName()
    {
        return APPPATH . sprintf('cache/%s-appContainerCache.php', Kohana::$environment);
    }

    /**
     * @return string
     */
    private function getContainerCacheClassName()
    {
        return 'appContainerCache';
    }

    /**
     * @return bool
     */
    private function isDebug()
    {
        return Kohana::$environment >= Kohana::DEVELOPMENT;
    }
}
