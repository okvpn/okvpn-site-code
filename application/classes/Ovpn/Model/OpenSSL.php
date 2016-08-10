<?php 

/**
* 
*/
class Model_OpenSSL extends Model
{

    protected $_openssl_dir = APPPATH . 'openssl/';
    

    public function genConfig(Model_Host $host, $client)
    {
        if (!function_exists('shell_exec')) {
            throw new Exception('Function shell_exec() do not exsits');
        }

        $easyrsa = $this->_openssl_dir . $host->getName();

        if (!file_exists($easyrsa . '/easyrsa')) {
            throw new Exception(sprintf('File %s not found', $easyrsa));
        }

        $payload = "cd $easyrsa && bash easyrsa build-client-full $client nopass";
        shell_exec($payload);

        $needs = $this->getConfigResources($host, $client);
        $content = file_get_contents($needs['conf']);
        $content .= "<ca>"   . PHP_EOL . file_get_contents($needs['ca'])  . "</ca>"   . PHP_EOL;
        $content .= "<cert>" . PHP_EOL . file_get_contents($needs['crt']) . "</cert>" . PHP_EOL;
        $content .= "<key>"  . PHP_EOL . file_get_contents($needs['key']) . "</key>"  . PHP_EOL;

        return $content;
    }

    protected function getConfigResources(Model_Host $host, $client)
    {
        $resources = [
            'conf' => $this->_openssl_dir . $host->getName() . "/client-common.txt", 
            'crt'  => $this->_openssl_dir . $host->getName() . "/pki/issued/$client.crt",
            'key'  => $this->_openssl_dir . $host->getName() . "/pki/private/$client.key",
            'ca'   => $this->_openssl_dir . $host->getName() . "/pki/ca.crt",
        ];

        foreach ($resources as $file) {
            if (!file_exists($file)) {
                throw new Exception(sprintf('File %s not found', $file));
            }
        }

        return $resources;
    }


}