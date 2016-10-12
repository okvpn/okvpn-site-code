<?php

namespace Okvpn\OkvpnBundle\Core;

interface ConfigInterface
{
    /**
     * @param $name
     * @return mixed
     */
    public function get($name);
}
