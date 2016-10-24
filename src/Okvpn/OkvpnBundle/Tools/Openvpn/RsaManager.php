<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn;

use Okvpn\OkvpnBundle\Tools\Openvpn\Config\Context;

class RsaManager implements RsaManagerInterface
{
    /**
     * Cumulative resource manager
     * @var array
     */
    protected $resource;

    /**
     * @var Context
     */
    protected $context;

    protected $init = false;
    
    protected $opensslDir;

    public function __construct(Context $context)
    {
        $this->context = $context;
        
        $this->opensslDir = DOCROOT . sprintf("var/openssl/%s/", $context->getHostname());
        if (! file_exists($this->opensslDir)) {
            throw new \Exception('Openssl dir not exist');
        }

        if (! file_exists($this->pathToCa())) {
            throw new \Exception(
                sprintf('EasyRSA must be init for host "%s".', $context->getHostname())
            );
        }

        $this->resource['ca'] = file_get_contents($this->pathToCa());

        if (file_exists($this->pathToClientCert($context->getClient())) &&
            file_exists($this->pathToClientKey($context->getClient()))
        ) {
            $this->resource['key'] = file_get_contents($this->pathToClientKey($context->getClient()));
            $this->resource['cert'] = file_get_contents($this->pathToClientCert($context->getClient()));
            $this->init = true;
        }
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        if ($this->init) {
            return;
        }
        
        $payload = $this->getCommandForGenerateCert($this->context->getClient());
       
        if (! function_exists('shell_exec')) {
            throw new \Exception('shell_exec');
        }
        
        shell_exec($payload);

        if (file_exists($this->pathToClientCert($this->context->getClient())) &&
            file_exists($this->pathToClientKey($this->context->getClient()))
        ) {
            $this->resource['key'] = file_get_contents($this->pathToClientKey($this->context->getClient()));
            $this->resource['cert'] = file_get_contents($this->pathToClientCert($this->context->getClient()));
            $this->init = true;
        } else {
            throw new \RuntimeException('Openssl not installed. Check needs regiments');
        }
        return;
    }

    /**
     * @inheritdoc
     */
    public function has($name)
    {
        return array_key_exists($name, $this->resource);
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        if (! $this->has($name)) {
            throw new \InvalidArgumentException(sprintf('Parameters "%s" do not exist', $name));
        }
        
        return $this->resource[$name];
    }

    protected function pathToClientKey($client)
    {
        return $this->opensslDir . "pki/private/$client.key";
    }

    protected function pathToClientCert($client)
    {
        return $this->opensslDir . "pki/issued/$client.crt";
    }

    protected function pathToCa()
    {
        return $this->opensslDir . 'pki/ca.crt';
    }

    private function getCommandForGenerateCert($name)
    {
        return <<<BASH
cd $this->opensslDir
bash easyrsa.sh build-client-full $name nopass  > /dev/null 2>&1
BASH;
    }
}
