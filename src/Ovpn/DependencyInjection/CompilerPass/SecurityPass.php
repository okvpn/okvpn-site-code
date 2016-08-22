<?php

namespace Ovpn\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SecurityPass implements CompilerPassInterface
{

    protected $name = 'ovpn_token.storage';

    protected $tagName = 'secure.token';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has($this->name)) {
            $definition = $container->getDefinition($this->name);

            foreach ($container->findTaggedServiceIds($this->tagName) as $id => $name) {
                $definition->addMethodCall('addToken', [new Reference($id)]);
            }
        }
    }
}