<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn\Config;

class UpdExtension extends AbstractExtension
{

    /**
     * {@inheritdoc}
     */
    protected function create(Context $context)
    {
        return $this->configBuilder
            ->addClientMode()
            ->addDev()
            ->addProto('upd')
            ->addSndbuf()
            ->addRcvbuf()
            ->addRemote($this->getDomainName($context))
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
