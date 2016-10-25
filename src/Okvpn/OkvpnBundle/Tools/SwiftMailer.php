<?php

namespace Okvpn\OkvpnBundle\Tools;

use Okvpn\OkvpnBundle\Core\Config;
use Okvpn\OkvpnBundle\Tools\Exception\NotDeliveredException;

class SwiftMailer implements MailerInterface
{
    /** @var  \Swift_Mailer */
    protected $mailer;
    
    /** @var  Config */
    protected $config;

    public function __construct(Config $config)
    {
        $config = $config->get('mailer');

        $transport = \Swift_SmtpTransport::newInstance($config['transport_host'], $config['transport_port'])
            ->setUsername($config['username'])
            ->setPassword($config['password']);

        $this->mailer = \Swift_Mailer::newInstance($transport);
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function send($message)
    {
        if (!$message instanceof \Swift_Message) {
            throw new \InvalidArgumentException(
                sprintf(
                    "SwiftMailer::send \\Swift_Message supported only, but %s given",
                    is_object($message) ? get_class($message) : gettype($message)
                )
            );
        }

        if (!$message->getFrom()) {
            $message->setFrom($this->config['sender']);
        }
        
        if (!$this->mailer->send($message)) {
            throw new NotDeliveredException(sprintf('Mailer provider return code 0'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMailProvider()
    {
        return $this->mailer;
    }
}
