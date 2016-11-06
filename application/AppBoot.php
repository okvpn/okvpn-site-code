<?php

use Okvpn\Bridge\Kohana\Boot\AbstractBoot;

class AppBoot extends AbstractBoot
{

    /**
     * {@inheritdoc}
     */
    protected function getBundles($envelopment)
    {
        $bundles = [
            new Okvpn\OkvpnBundle\OkvpnFramework(),
        ];

        if ($envelopment == 'test') {
            $bundles = array_merge(
                $bundles,
                [
                    new Okvpn\TestFrameworkBundle\OkvpnTestBundle()
                ]
            );
        }

        return $bundles;
    }

    /**
     * Activate kohana rouging rule
     *
     * @param string $envelopment
     */
    protected function loadRouter($envelopment)
    {
        Route::set('main', '<action>(/<token>)', array('action' => 'faq|guide|signup|blockchain|csrf|content|proxy'))
            ->defaults(array(
                'controller' => 'main',
            ));

        Route::set('api', 'api/v2/<action>(/<format>)')
            ->defaults(array(
                'controller' => 'api',
                'action' => 'index',
                'format' => 'json',
            ));

        Route::set('default', '(<controller>(/<action>(/<token>)))')
            ->defaults(array(
                'controller' => 'welcome',
                'action' => 'index',
            ));
    }

    /**
     * @param string $envelopment
     */
    protected function loadKohanaModules($envelopment)
    {
        Kohana::modules(
            [
                'database' => MODPATH . 'database',   // Database access
                'orm' => MODPATH . 'orm',        // Object Relationship Mapping
            ]
        );
    }
}
