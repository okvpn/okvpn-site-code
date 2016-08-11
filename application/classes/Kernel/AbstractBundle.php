<?php

namespace Kernel;

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
        return $class->newInstance();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}