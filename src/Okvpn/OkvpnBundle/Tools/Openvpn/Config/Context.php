<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

class Context
{
    /** @var  string $client */
    protected $client;
    
    /** @var string $hostname */
    protected $hostname;

    /**
     * @param $client
     * @param $hostname
     */
    public function __construct($client, $hostname)
    {
        $this->hostname = $hostname;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }
}
