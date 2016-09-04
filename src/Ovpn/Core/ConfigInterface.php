<?php

namespace Ovpn\Core;


interface ConfigInterface
{
    /**
     * @param $name
     * @return mixed
     */
    public function get($name);
}