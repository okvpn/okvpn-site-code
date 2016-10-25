<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Tools\Openvpn\ConfigBuilderInterface;
use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManager;
use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManagerInterface;

abstract class AbstractExtension
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
        $this->initRsaManager($context);
        $openvpnConfiguration = $this->create($context);

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
    protected function initRsaManager(Context $context)
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
     * @param $context
     * @return string
     */
    protected function getDomainName(Context $context)
    {
        return sprintf("%s.%s", $context->getHostname(), $this->config->get(self::VPN_DOMAIN_PARAM));
    }

    /**
     * @param Context $context
     * @return string
     */
    abstract protected function create(Context $context);
}
