<?php

namespace Ovpn\Tools\Openvpn;


class RsaManager implements RsaManagerInterface
{
    /**
     * Cumulative resource manager
     * @var array
     */
    protected $resource;

    /**
     * @var string
     */
    protected $client;

    /**
     * @var string
     */
    protected $hostname;

    protected $init = false;
    
    protected $opensslDir;

    public function __construct($client, $hostname)
    {
        $this->opensslDir = DOCROOT . "var/openssl/$hostname/";
        if (! file_exists($this->opensslDir)) {
            throw new \Exception('Openssl dir not exist');
        }

        if (! file_exists($this->pathToCa())) {
            throw new \Exception(sprintf('EasyRSA must be init for host "%s".', $hostname));
        }

        $this->resource['ca'] = file_get_contents($this->pathToCa());

        if (file_exists($this->pathToClientCert($client)) &&
            file_exists($this->pathToClientKey($client))) {

            $this->resource['key'] = file_get_contents($this->pathToClientKey($client));
            $this->resource['cert'] = file_get_contents($this->pathToClientCert($client));
            $this->init = true;
        }

        $this->client = $client;
        $this->hostname = $hostname;
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        if ($this->init) {
            return;
        }
        
        $payload = $this->getCommandForGenerateCert($this->client);
       
        if (! function_exists('shell_exec')) {
            throw new \Exception('shell_exec');
        }
        
        shell_exec($payload);

        if (file_exists($this->pathToClientCert($this->client)) &&
            file_exists($this->pathToClientKey($this->client))) {

            $this->resource['key'] = file_get_contents($this->pathToClientKey($this->client));
            $this->resource['cert'] = file_get_contents($this->pathToClientCert($this->client));
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
            throw new \InvalidArgumentException(sprintf('Parameters "%s" do not exist'));
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
bash easyrsa.sh build-client-full $name nopass
BASH;
    }

}