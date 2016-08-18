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
    
    public function get($name, $default = null)
    {
        return $this->kohanaConfig->get($name, $default);
    }

    public function getConfig()
    {
        return $this->kohanaConfig;
    }
}