<?php

namespace Ovpn\Tools\Openvpn;


class ConfigBuilder implements ConfigBuilderInterface
{

    protected $cumulativeConfig = '';

    /**
     * @inheritdoc
     */
    public function addCa($ca, $mode = 'full')
    {
        if ($mode == 'full') {
            $this->cumulativeConfig .= '<ca>' . PHP_EOL . $ca . PHP_EOL . '</ca>' . PHP_EOL;
        } else {
            $this->cumulativeConfig .= "ca $ca" . PHP_EOL;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addKey($key, $mode = 'full')
    {
        if ($mode == 'full') {
            $this->cumulativeConfig .= '<key>' . PHP_EOL . $key . PHP_EOL . '</key>' . PHP_EOL;
        } else {
            $this->cumulativeConfig .= "key $key" . PHP_EOL;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCert($cert, $mode = 'full')
    {
        if ($mode == 'full') {
            $this->cumulativeConfig .= '<cert>' . PHP_EOL . $cert . PHP_EOL . '</cert>' . PHP_EOL;
        } else {
            $this->cumulativeConfig .= "cert $cert" . PHP_EOL;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addClientMode()
    {
        $this->cumulativeConfig .= 'client' . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addDev($option = 'tun')
    {
        $this->cumulativeConfig .= "dev $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addProto($option = 'tcp')
    {
        $this->cumulativeConfig .= "proto $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addNobind()
    {
        $this->cumulativeConfig .= 'nobind' . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addPersistKey()
    {
        $this->cumulativeConfig .= 'persist-key' . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addPersistTun()
    {
        $this->cumulativeConfig .= 'persist-tun' . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRequireRemoteCertSign($option = 'server')
    {
        $this->cumulativeConfig .= "remote-cert-tls $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRemote($host, $port = '1194')
    {
        $this->cumulativeConfig .= "remote $host $port" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addSndbuf($option = '0')
    {
        $this->cumulativeConfig .= "sndbuf $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRcvbuf($option = '0')
    {
        $this->cumulativeConfig .= "rcvbuf $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addComplzo()
    {
        $this->cumulativeConfig .= 'comp-lzo' . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addVerb($option = '3')
    {
        $this->cumulativeConfig .= "verb $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addResolvRetry($option = 'infinite')
    {
        $this->cumulativeConfig .= "resolv-retry $option" . PHP_EOL;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build()
    {
        $config = $this->cumulativeConfig;
        return $config;
    }
}