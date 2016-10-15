<?php

namespace Okvpn\Bridge\Kohana\Factory;

use Symfony\Component\DependencyInjection\Container;

use Okvpn\Bridge\Kohana\Kernel\CumulativeResourceManager;

class ContainerCreater
{
    /**
     * @return Container
     */
    public static function create()
    {
        $container = CumulativeResourceManager::getInstance()->getContainer();
        return $container;
    }
}