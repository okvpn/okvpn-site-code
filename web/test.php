<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('classes', __DIR__ . '/../application');
$loader->register();


class Kernel
{
    public function getContainer()
    {
        return $this->getContainerBuilder();
    }

    public function getContainerBuilder()
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../application/classes/Resources/config'));
        $loader->load('services.yml');

        return $container;
    }
}

(new Kernel())->getContainer()->get('ok_user.entity');