<?php

namespace Okvpn\OkvpnBundle\Tools;

interface MailerInterface
{
    /**
     * @param mixed $message
     * @return mixed
     */
    public function send($message);

    /**
     * @return Object
     */
    public function getMailProvider();
}
