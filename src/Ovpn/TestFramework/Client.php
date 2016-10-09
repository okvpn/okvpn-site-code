<?php

namespace Ovpn\TestFramework;

use Kernel\CumulativeResourceManager;
use Symfony\Component\DependencyInjection\Container;
use Request;

class Client
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Container
     */
    protected $container;
    
    public function __construct()
    {
        $this->container = CumulativeResourceManager::getInstance()->getContainer();
    }

    /**
     * @param $method
     * @param $url
     * @param array $parameters
     * @param array $applicationData
     * @param array $cookie
     *
     * @return $this
     */
    public function prepareClient(
        $method,
        $url,
        array $parameters = [],
        array $applicationData = [],
        array $cookie = []
    ) {
        Request::$user_agent = $applicationData['user_agent'] ?? DefaultClientParam::USER_AGENT;
        Request::$client_ip = $applicationData['client_ip'] ?? DefaultClientParam::CLIENT_IP;
        Request::$initial = $this->request = $this->buildRequest($url);
        
        $this->request
            ->protocol($applicationData['protocol'] ?? DefaultClientParam::PROTOCOL);
        $method = strtoupper($method);

        if ($method == 'POST') {
            $this->request->post($parameters);
        } else {
            $this->request->query($parameters);
        }

        $this->request->method($method);
        $this->request->cookie($cookie);

        $_COOKIE = $cookie;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if ($this->request instanceof Request) {
            return $this->request;
        }

        throw new \RuntimeException('Client must be init');
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $url
     *
     * @return Request
     */
    protected function buildRequest($url)
    {
        Request::$initial = null;

        return new Request($url);
    }
}
