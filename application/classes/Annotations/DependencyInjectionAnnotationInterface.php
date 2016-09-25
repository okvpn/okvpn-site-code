<?php

namespace Annotations;


interface DependencyInjectionAnnotationInterface
{
    /**
     * Get service name for inject to controller
     * 
     * @return string
     */
    public function getServiceName();
}