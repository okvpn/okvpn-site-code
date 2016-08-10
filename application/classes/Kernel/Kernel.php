<?php
namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Kernel extends AbstractKernel
{
    /**
     * @inheritdoc
     */
    public function getContainerBuilder()
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator(APPPATH . 'classes/Ovpn/Resources/config'));
        $loader->load('services.yml');

        return $container;
    }
}