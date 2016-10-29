<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

class TcpExtension extends AbstractExtension
{
    const REMOTE_PORT = '443';

    /**
     * {@inheritdoc}
     */
    protected function create(Context $context)
    {
        return $this->configBuilder
            ->addClientMode()
            ->addDev()
            ->addProto('tcp')
            ->addRemote($this->getDomainName($context), self::REMOTE_PORT)
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
