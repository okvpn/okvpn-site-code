<?php

namespace Kernel;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class ProxyContainer extends Container
{
    /**
     * @var bool
     */
    protected $isInitialized;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct()
    {
        $container = Kernel::getContainer();

        if ($container instanceof ContainerBuilder) {
            $this->isInitialized = true;
            $this->container = $container;
        } else {
            $this->container = new ContainerBuilder();
        }

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $this->container->get($id, $invalidBehavior);
    }
}