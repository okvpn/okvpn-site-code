<?php

namespace Okvpn\OkvpnBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Kernel\AbstractBundle;
use Okvpn\OkvpnBundle\DependencyInjection\CompilerPass\SecurityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OkvpnBundle extends AbstractBundle
{
    protected $name = 'Okvpn\OkvpnBundle';

    public function build(ContainerBuilder $container)
    {
        AnnotationRegistry::registerAutoloadNamespace(
            'Annotations\DependencyInjectionAnnotation',
            APPPATH . 'classes'
        );
        
        $container->addCompilerPass(new SecurityPass());
    }
}
