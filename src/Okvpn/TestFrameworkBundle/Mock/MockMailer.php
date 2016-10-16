<?php

namespace Okvpn\TestFrameworkBundle\Mock;

use Okvpn\OkvpnBundle\Tools\MailerInterface;

class MockMailer implements MailerInterface
{

    /**
     * @param array $payload
     * @return mixed
     */
    public function sendMessage(array $payload)
    {
        
    }

    /**
     * @return Object
     */
    public function getMailProvider()
    {
        
    }
}
