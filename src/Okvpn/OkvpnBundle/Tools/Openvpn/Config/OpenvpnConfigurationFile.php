<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

final class OpenvpnConfigurationFile
{
    /** @var  string */
    protected $rootCa;
    
    /** @var  string */
    protected $privateKey;
    
    /** @var  string */
    protected $certificate;
    
    /** @var  string */
    protected $configuration;
    
    public function __construct($rootCa, $privateKey, $certificate, $configuration)
    {
        $this->rootCa = $rootCa;
        $this->privateKey = $privateKey;
        $this->certificate = $certificate;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getCa()
    {
        return $this->rootCa;
    }

    /**
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
