<?php

namespace Okvpn\OkvpnBundle;

use Okvpn\Bridge\Kohana\Kernel\AbstractBundle;
use Okvpn\OkvpnBundle\DependencyInjection\CompilerPass\SecurityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OkvpnFramework extends AbstractBundle
{
    protected $name = 'Okvpn\OkvpnBundle';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SecurityPass());
    }
}
