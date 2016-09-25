<?php
namespace Kernel;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends AbstractKernel
{
    /**
     * @inheritdoc
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
        $this->buildContainer($container);
    }

    public function buildContainer(ContainerBuilder $container)
    {
        /** @var AbstractBundle $bundle */
        foreach (Kernel::$bundles as $bundle) {
            $bundle->build($container);
        }
    }
}