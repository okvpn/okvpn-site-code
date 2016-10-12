<?php

namespace Okvpn\OkvpnBundle\Tools\Openvpn;

interface RsaManagerInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * @param string $name
     * @return bool
     */
    public function has($name);
}
