<?php

namespace Ovpn\Core;


class Config
{
    protected $kohanaConfig;
    
    protected $defaultConfig = 'info';
    
    protected $defaultParam;

    public function __construct($defaultConfig = null)
    {
        if ($defaultConfig) {
            $this->defaultConfig = $defaultConfig;
        }
        $this->kohanaConfig = \Kohana::$config->load($this->defaultConfig);
        $this->defaultParam = \Kohana::$config->load('parameters');
    }
    
    public function get($baseName, $default = null)
    {
        $name = preg_split('/:/', $baseName);
        $config =  $this->kohanaConfig->get(array_shift($name), $default);

        if (! is_array($config)) {
            return (null === $config) ? $default : $config;
        }

        foreach ($name as $configKeyName) {
            if (array_key_exists($configKeyName, $config)) {
                $config = $config[$configKeyName];
            } else {
                throw new \Exception(
                    sprintf('The config key"%s" nor exsist in "%s"', $baseName, $this->defaultConfig));
            }

            if (! is_array($config)) {
                if (! preg_match('/^%(.+)%$/', $config, $match)) {
                    return $config;
                }
                
                return $this->getParameters($match[1]);
            }
        }

        return $default;
    }
    
    protected function getParameters($name)
    {
        $value = $this->defaultParam->get($name, null);
        if  (null === $value) {
            throw new \Exception(sprintf('Config parameters "%s" not found in parameters.php'));
        }
        return $value;
    }

    public function getConfig()
    {
        return $this->kohanaConfig;
    }
}