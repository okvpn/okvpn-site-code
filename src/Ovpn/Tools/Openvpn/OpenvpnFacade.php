<?php

namespace Ovpn\Tools\Openvpn;


class OpenvpnFacade implements RsaManagerInterface
{
    /**
     * @var RsaManager
     */
    protected $rsaManager;

    /**
     * @var ConfigBuilder
     */
    protected $builder;
    
    public function __construct()
    {
        $this->builder = new ConfigBuilder();
    }

    public function setClientName($name, $hostname)
    {
        $rsa = new RsaManager($name, $hostname);
        $rsa->init();

        $this->rsaManager = $rsa;
    }

    /**
     * @return RsaManager
     */
    public function getRsa()
    {
        if (!$this->rsaManager) {
            throw new \RuntimeException('Set client name must be run before this operation');
        }

        return $this->rsaManager;
    }

    /**
     * @return ConfigBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    public function buildCommonUpdConfig($domainname)
    {
        if (!$this->rsaManager) {
            throw new \RuntimeException('Set client name must be run before this operation');
        }

        return $this->builder
            ->addClientMode()
            ->addDev()
            ->addProto('upd')
            ->addSndbuf()
            ->addRcvbuf()
            ->addRemote($domainname)
            ->addRequireRemoteCertSign()
            ->addPersistKey()
            ->addPersistTun()
            ->addResolvRetry()
            ->addVerb()
            ->addNobind()
            ->addComplzo()
            ->addCa($this->rsaManager->get('ca'))
            ->addCert($this->rsaManager->get('cert'))
            ->addKey($this->rsaManager->get('key'))
            ->build();
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        if (!$this->rsaManager) {
            throw new \RuntimeException('Set client name must be run before this operation');
        }

        return $this->rsaManager->get($name);
    }

    /**
     * @inheritdoc
     */
    public function has($name)
    {
        if (!$this->rsaManager) {
            throw new \RuntimeException('Set client name must be run before this operation');
        }

        return $this->rsaManager->has($name);
    }
}