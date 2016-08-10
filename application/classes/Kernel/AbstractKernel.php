<?php
namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractKernel
{
    protected static $bundles;

    public static function registrationBundle(array $bundles)
    {
        if (static::$bundles) {
            static::$bundles = $bundles;
        }

        throw new \Exception('Bundles are already registered');
    }

    public function getContainer()
    {
        return $this->getContainerBuilder();
    }

    /**
     * @return ContainerBuilder
     */
    abstract public function getContainerBuilder();
}