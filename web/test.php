<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('Ovpn', __DIR__ . '/../application/classes');
$loader->register();


/*class Kernel
{
    public function getContainer()
    {
        return $this->getContainerBuilder();
    }

    public function getContainerBuilder()
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container,
            new FileLocator(__DIR__ . '/../application/classes/Ovpn/Resources/config'));
        $loader->load('services.yml');

        return $container;
    }
}*/

$user = (new Kernel())->getContainer()->get('ovpn_user.entity');