<?php

namespace Ovpn\Tools;


interface MailerInterface
{
    /**
     * @param array $payload
     * @return mixed
     */
    public function sendMessage(array $payload);

    /**
     * @return Object
     */
    public function getMailProvider();
}