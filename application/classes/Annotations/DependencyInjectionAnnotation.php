<?php

namespace Annotations;

/** @Annotation */
class DependencyInjectionAnnotation implements DependencyInjectionAnnotationInterface
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