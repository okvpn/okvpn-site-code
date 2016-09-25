<?php

namespace Ovpn;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Kernel\AbstractBundle;
use Ovpn\DependencyInjection\CompilerPass\SecurityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OvpnBundle extends AbstractBundle
{
    protected $name = 'Ovpn';

    public function build(ContainerBuilder $container)
    {
        AnnotationRegistry::registerAutoloadNamespace('Annotations\DependencyInjectionAnnotation', APPPATH . 'classes' );
        
        $container->addCompilerPass(new SecurityPass());
    }
}