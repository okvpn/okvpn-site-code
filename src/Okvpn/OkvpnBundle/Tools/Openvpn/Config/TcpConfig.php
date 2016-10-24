<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

use Okvpn\OkvpnBundle\Tools\Openvpn\RsaManagerInterface;

class TcpConfig extends AbstractConfig
{
    const REMOTE_PORT = '443';

    /**
     * {@inheritdoc}
     */
    protected function create(RsaManagerInterface $rsaManager)
    {
        return $this->configBuilder
            ->addClientMode()
            ->addDev()
            ->addProto('tcp')
            ->addRemote($this->getDomainName(), self::REMOTE_PORT)
            ->addRequireRemoteCertSign()
            ->addPersistKey()
            ->addPersistTun()
            ->addResolvRetry()
            ->addVerb()
            ->addNobind()
            ->addCa($this->rsaManager->get('ca'))
            ->addCert($this->rsaManager->get('cert'))
            ->addKey($this->rsaManager->get('key'))
            ->getConfig();
    }
}
