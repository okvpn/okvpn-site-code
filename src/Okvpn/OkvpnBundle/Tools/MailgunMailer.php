<?php

namespace Okvpn\OkvpnBundle\Tools;

use Mailgun\Mailgun;
use Okvpn\OkvpnBundle\Core\Config;

/**
 * Class MailgunMailer
 *
 * @deprecated
 */
class MailgunMailer implements MailerInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Mailgun
     */
    protected $mailProvider;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->init();
    }

    public function init()
    {
        if ($this->mailProvider) {
            throw new \LogicException('The mail provider has been already init');
        }

        $this->mailProvider = new Mailgun($this->config->get('mailgun:key'));
    }

    /**
     * {@inheritdoc}
     */
    public function send($payload)
    {
        if (!is_array($payload)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "MailgunMailer::send array supported only, but %s given",
                    is_object($payload) ? get_class($payload) : gettype($payload)
                )
            );
        }

        if (! array_key_exists('from', $payload)) {
            $payload['from'] = $this->buildFromHeader();
        }

        return $this->mailProvider->sendMessage(
            $this->config->get('mailgun:site'),
            $payload
        );
    }

    /**
     * @return Mailgun
     */
    public function getMailProvider()
    {
        return $this->mailProvider;
    }

    private function buildFromHeader()
    {
        $from = $this->config->get('mailgun:from_email');
        return $this->config->get('mailgun:from_alias') . " <$from>";
    }
}
