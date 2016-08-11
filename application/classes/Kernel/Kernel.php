<?php
namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends AbstractKernel
{
    /**
     * @inheritdoc
     */
    public function getContainerBuilder()
    {
        $container = new ContainerBuilder();
        $this->prepareContainer($container);

        return $container;
    }

    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function prepareContainer(ContainerBuilder $container)
    {
        if (!Kernel::$bundles) {
            throw new \Exception('');
        }
        
        /** @var AbstractBundle $bundle */
        foreach (Kernel::$bundles as $bundle) {
            $bundle->getExtension()->load([], $container);
        }
    }
}