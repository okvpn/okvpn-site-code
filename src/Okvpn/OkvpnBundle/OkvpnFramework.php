<?php

namespace Okvpn\OkvpnBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

use Okvpn\Bridge\Kohana\Kernel\AbstractBundle;
use Okvpn\OkvpnBundle\DependencyInjection\CompilerPass\SecurityPass;

class OkvpnFramework extends AbstractBundle
{
    protected $name = 'Okvpn\OkvpnBundle';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SecurityPass());
        $container->addCompilerPass(new RegisterListenersPass());
    }
}
