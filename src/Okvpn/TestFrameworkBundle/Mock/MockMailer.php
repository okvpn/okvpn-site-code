<?php

namespace Okvpn\TestFrameworkBundle\Mock;

use Okvpn\OkvpnBundle\Tools\MailerInterface;

class MockMailer implements MailerInterface
{
    use MockTrait;

    /**
     * @param array $payload
     * @return mixed
     */
    public function sendMessage(array $payload)
    {
        $this->saveInvokeValue('sendMessage', $payload);
        if ($this->isEnableParentMethod('sendMessage')) {
            return true;
        }

        if (null === self::$mockObject) {
            return true;
        }
        
        return self::$mockObject->sendMessage($payload);
    }

    /**
     * @return Object
     */
    public function getMailProvider()
    {
        if ($this->isEnableParentMethod('getMailProvider')) {
            return true;
        }

        if (null === self::$mockObject) {
            return true;
        }

        return self::$mockObject->getMailProvider();
    }
}
