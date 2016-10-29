<?php

namespace Okvpn\OkvpnBundle\Tools;

interface MailerInterface
{
    /**
     * @param mixed $message
     * @return bool
     */
    public function send($message);

    /**
     * @return Object
     */
    public function getMailProvider();
}
