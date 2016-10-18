<?php

namespace Okvpn\Core\Annotations;

/** @Annotation */
class Inject implements InjectInterface
{
    /**
     * @var string
     */
    public $service;

    /**
     * @inheritdoc
     */
    public function getServiceName()
    {
        return $this->service;
    }
}
