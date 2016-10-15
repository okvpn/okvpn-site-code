<?php

namespace Okvpn\Bridge\Kohana\Proxy;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Okvpn\Bridge\Kohana\Kernel\CumulativeResourceManager as ResourceManager;

class ProxyContainer extends Container
{
    /**
     * @var bool
     */
    protected $isInitialized;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct()
    {
        $container = ResourceManager::getInstance()->getContainer();

        if ($container instanceof ContainerInterface) {
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
        return $this->container->get($id, $invalidBehavior);
    }
}