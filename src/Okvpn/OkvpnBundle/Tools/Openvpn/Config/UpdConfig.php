<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManagerInterface;

class UpdConfig extends AbstractConfig
{

    /**
     * {@inheritdoc}
     */
    protected function create(RsaManagerInterface $rsaManager)
    {
        return $this->configBuilder
            ->addClientMode()
            ->addDev()
            ->addProto('upd')
            ->addSndbuf()
            ->addRcvbuf()
            ->addRemote($this->getDomainName())
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
            ->getConfig();
    }
}
