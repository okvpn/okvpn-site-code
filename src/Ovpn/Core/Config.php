<?php

namespace Ovpn\Core;

use Config as BaseConfig;


class Config implements ConfigInterface
{
    protected $kohanaConfig;
    
    protected $defaultConfig = 'info';
    
    protected $defaultParam;

    public function __construct(BaseConfig $config = null, BaseConfig $parameters = null, $defaultConfig = null)
    {
        if ($defaultConfig) {
            $this->defaultConfig = $defaultConfig;
        }

        if ($config === null) {
            $config = \Kohana::$config->load($this->defaultConfig);
        }
        if ($parameters === null) {
            $parameters = \Kohana::$config->load('parameters');
        }
        
        $this->kohanaConfig = $config;
        $this->defaultParam = $parameters;
    }

    /**
     * @param $baseName
     * @return mixed|null
     * @throws \Exception
     */
    public function get($baseName)
    {
        $name = preg_split('/:/', $baseName);
        $config =  $this->kohanaConfig->get(array_shift($name));

        foreach ($name as $configKeyName) {
            if (array_key_exists($configKeyName, $config)) {
                $config = $config[$configKeyName];
            } else {
                throw new \InvalidArgumentException(
                    sprintf('The config key"%s" nor exist in "%s"', [$baseName, $this->defaultConfig]));
            }
        }
        
        if (is_array($config)) {
            $closing = function ($conf) use (&$closing) {
                
                $mapping = [];
                foreach ($conf as $key => $val) {
                    if (is_array($val)) {
                        $mapping[$key] = $closing($val);

                    } elseif (! preg_match('/^%(.+)%$/', $val, $match)) {
                        $mapping[$key] = $val;

                    } else {
                        $mapping[$key] = $this->getParameters($match[1]);
                    }
                }
                return $mapping;
            };
            
            return $closing($config);
            
        } else {
            if (! preg_match('/^%(.+)%$/', $config, $match)) {
                return $config;
            }

            return $this->getParameters($match[1]);
        }
    }
    
    protected function getParameters($name)
    {
        $value = $this->defaultParam->get($name, null);
        if  (null === $value) {
            throw new \Exception(sprintf('Config parameters "%s" not found in parameters.php', $name));
        }
        return $value;
    }

    public function getConfig()
    {
        return $this->kohanaConfig;
    }
}