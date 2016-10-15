<?php

namespace Okvpn\TestFrameworkBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Okvpn\TestFrameworkBundle\DependencyInjection\CompilerPass\TestIsolationClassPass;
use Okvpn\Bridge\Kohana\Kernel\AbstractBundle;

class OkvpnTestBundle extends AbstractBundle
{

    protected $name = 'Okvpn\TestFrameworkBundle';
    
    protected $priority = 1000;
    
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TestIsolationClassPass());
    }
}
