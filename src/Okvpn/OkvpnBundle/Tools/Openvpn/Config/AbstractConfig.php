<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Tools\Openvpn\ConfigBuilderInterface;
use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManager;
use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManagerInterface;

abstract class AbstractConfig
{
    const VPN_DOMAIN_PARAM = 'vpn_domain';

    /** @var  RsaManagerInterface */
    protected $rsaManager;

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
     * @param $client
     * @param $hostname
     * @return mixed
     */
    public function createOpenvpnConfiguration($client, $hostname)
    {
        $context = new Context($client, $hostname);
        $openvpnConfiguration = $this->create($this->getRsaManager($context));

        return new OpenvpnConfigurationFile(
            $this->rsaManager->get('ca'),
            $this->rsaManager->get('key'),
            $this->rsaManager->get('cert'),
            $openvpnConfiguration
        );
    }

    /**
     * @param Context $context
     * @return RsaManager
     */
    protected function getRsaManager(Context $context)
    {
        if (null === $this->rsaManager) {
            $this->rsaManager = $this->createRsaManager($context);
        }

        return $this->rsaManager;
    }

    /**
     * @param Context $context
     * @return RsaManagerInterface
     */
    protected function createRsaManager(Context $context)
    {
        $rsaManager = new RsaManager($context);
        $rsaManager->init();
        return $rsaManager;
    }

    /**
     * Return domain name for used as remote param into client.ovpn config
     *
     * @return string
     */
    protected function getDomainName()
    {
        return $this->config->get(self::VPN_DOMAIN_PARAM);
    }

    /**
     * @param RsaManagerInterface $rsaManager
     * @return mixed
     */
    abstract protected function create(RsaManagerInterface $rsaManager);
}
