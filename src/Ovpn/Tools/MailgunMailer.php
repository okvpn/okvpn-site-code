<?php

namespace Ovpn\Tools;


use Mailgun\Mailgun;
use Ovpn\Core\Config;

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
    }

    public function initMailProvider(MailerInterface $provider = null)
    {
        if ($this->mailProvider) {
            throw new \LogicException('The mail provider has been already init');
        }

        $this->mailProvider = $provider ?? new Mailgun($this->config->get('mailgun:key'));
    }

    /**
     * @inheritdoc
     */
    public function sendMessage(array $payload)
    {
        if (! $this->mailProvider) {
            $this->initMailProvider();
        }

        if (! array_key_exists('from', $payload)) {
            $payload['from'] = $this->buildFromHeader();
        }
        return $this->mailProvider->sendMessage(
            $this->config->get('mailgun:site'), $payload);
    }

    /**
     * @return Mailgun
     */
    public function getMailProvider()
    {
        if (! $this->mailProvider) {
            $this->initMailProvider();
        }
        
        return $this->mailProvider;
    }

    private function buildFromHeader()
    {
        $from = $this->config->get('mailgun:from_email');
        return $this->config->get('mailgun:from_alias') . " <$from>";
    }

}