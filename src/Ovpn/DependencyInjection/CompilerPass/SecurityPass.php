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
        $sortedParsers = [];

        if (! $container->has($this->name)) {
            return;
        }

        $definition = $container->getDefinition($this->name);
        foreach ($container->findTaggedServiceIds($this->tagName) as $id => $args) {
            $def = $container->getDefinition($id);
            if ($def->isPublic()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" must be not public.', $id));
            }

            if ($def->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" must not be abstract.', $id));
            }

            foreach ($args as $name) {
                if (array_key_exists('priority', $name)) {
                    $priority = $name['priority'];
                } else {
                    throw new \InvalidArgumentException(
                        sprintf('Service "%s" must define the "priority" attribute on "%s" tags.', $id, $this->tagName));
                }

                $sortedParsers[$priority][] = $id;
            }
        }

        if (! empty($sortedParsers)) {
            ksort($sortedParsers);
            $sortedParsers = call_user_func_array('array_merge', $sortedParsers);

            foreach ($sortedParsers as $id) {
                $definition->addMethodCall('addToken', [new Reference($id)]);
            }
        }

    }
}