<?php

namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

abstract class AbstractBundle
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return Extension
     * @throws \Exception
     */
    public function getExtension()
    {
        $classNameExtension = ucfirst($this->name) . '\\DependencyInjection\\Extension';

        if (! class_exists($classNameExtension)) {
            throw new \Exception(sprintf('The Extension "%s" is not exsist', $classNameExtension));
        }

        $class = new \ReflectionClass($classNameExtension);
        /** @var Extension $extetion */
        $extetion = $class->newInstance();
        return $extetion;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    abstract public function build(ContainerBuilder $container);
}