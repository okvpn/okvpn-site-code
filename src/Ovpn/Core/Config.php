<?php

namespace Ovpn\Core;


class Config
{
    protected $kohanaConfig;
    
    protected $defaultConfig = 'info';

    public function __construct()
    {
        $this->kohanaConfig = \Kohana::$config->load($this->defaultConfig);
    }
    
    public function get($baseName, $default = null)
    {
        $name = preg_split('/:/', $baseName);
        $config =  $this->kohanaConfig->get(array_shift($name), $default);

        if (!is_array($config)) {
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
                return $config;
            }
        }

        return $default;
    }

    public function getConfig()
    {
        return $this->kohanaConfig;
    }
}