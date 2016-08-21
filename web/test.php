<?php
define('DOCROOT', realpath(dirname(__FILE__).'/..').DIRECTORY_SEPARATOR);

class RsaManager
{
    protected $resource;

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
    }

    /**
     * @param $client
     * @throws \Exception
     */
    public function init($client)
    {
        if ($this->init) {
            return;
        }

        $payload = $this->getCommandForGenerateCert($client);

        if (! function_exists('shell_exec')) {
            throw new \Exception('shell_exec');
        }

        shell_exec($payload);

        if (file_exists($this->pathToClientCert($client)) &&
            file_exists($this->pathToClientKey($client))) {

            $this->resource['key'] = file_get_contents($this->pathToClientKey($client));
            $this->resource['cert'] = file_get_contents($this->pathToClientCert($client));
            $this->init = true;
        } else {
            throw new \RuntimeException('easyrsa error');
        }
        return;
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

(new RsaManager('test-rsa', 'de2'))->init('test-rsa');