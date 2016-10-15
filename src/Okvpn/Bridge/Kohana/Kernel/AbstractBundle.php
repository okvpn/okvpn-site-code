<?php

namespace Okvpn\Bridge\Kohana\Kernel;

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
            throw new \Exception(sprintf('The Extension "%s" is not exist', $classNameExtension));
        }

        $class = new \ReflectionClass($classNameExtension);
        /** @var Extension $extension */
        $extension = $class->newInstance();
        return $extension;
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
