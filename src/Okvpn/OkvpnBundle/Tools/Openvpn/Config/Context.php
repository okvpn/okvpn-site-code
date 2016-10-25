<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

class Context
{
    const EASYRSA_CERT_EXPIRE = 90;
    
    /** @var  string $client */
    protected $client;
    
    /** @var string $hostname */
    protected $hostname;
    
    /** @var int  */
    protected $expire;

    /**
     * @param $client
     * @param $hostname
     * @param $expire
     */
    public function __construct($client, $hostname, $expire = self::EASYRSA_CERT_EXPIRE)
    {
        $this->hostname = $hostname;
        $this->client = $client;
        $this->expire = $expire;
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

    public function getExpire()
    {
        return $this->expire;
    }
}
