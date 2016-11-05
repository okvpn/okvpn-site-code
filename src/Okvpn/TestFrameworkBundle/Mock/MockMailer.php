<?php

namespace Okvpn\TestFrameworkBundle\Mock;

use Okvpn\OkvpnBundle\Tools\MailerInterface;
use Okvpn\TestFrameworkBundle\Mock\Fixtures\Mailer;

class MockMailer implements MailerInterface
{
    use MockTrait;

    /**
     * @param array $payload
     * @return mixed
     */
    public function send($payload)
    {
        $this->saveInvokeValue('send', $payload);
        if ($this->isEnableParentMethod('send')) {
            return true;
        }

        if (null === self::$mockObject) {
            return true;
        }
        
        return $this->getMockClass()->send($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function getMailProvider()
    {
        if ($this->isEnableParentMethod('getMailProvider')) {
            return true;
        }

        if (null === self::$mockObject) {
            return true;
        }

        return $this->getMockClass()->getMailProvider();
    }

    /**
     * @return Mailer
     */
    public static function getMockClass()
    {
        return self::getMock();
    }
}
