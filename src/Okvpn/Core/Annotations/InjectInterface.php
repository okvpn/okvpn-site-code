<?php

namespace Okvpn\Core\Annotations;

interface InjectInterface
{
    /**
     * Get service name for inject to controller
     *
     * @return string
     */
    public function getServiceName();
}
