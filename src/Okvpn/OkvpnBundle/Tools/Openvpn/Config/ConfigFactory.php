<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Tools\Openvpn\ConfigBuilderInterface;

class ConfigFactory
{
    /** @var  Config */
    protected $config;

    /** @var  ConfigBuilderInterface */
    protected $configBuilder;

    public function __construct(Config $config, ConfigBuilderInterface $configBuilder)
    {
        $this->config = $config;
        $this->configBuilder = $configBuilder;
    }

    /**
     * @param string $namedConfig
     * @return AbstractConfig
     */
    public function create($namedConfig)
    {
        $class = $this->nameConverter($namedConfig);
        return new $class($this->config, $this->configBuilder);
    }

    /**
     * @param string $name
     * @return string
     */
    private function nameConverter($name)
    {
        return sprintf(
            "Okvpn\\OkvpnBundle\\Tools\\Openvpn\\Config\\%sConfig",
            ucfirst(strtolower($name))
        );
    }
}
