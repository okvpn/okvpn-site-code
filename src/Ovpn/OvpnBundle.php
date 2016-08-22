<?php

namespace Ovpn;

use Kernel\AbstractBundle;
use Ovpn\DependencyInjection\CompilerPass\SecurityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OvpnBundle extends AbstractBundle
{
    protected $name = 'Ovpn';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SecurityPass());
    }
}