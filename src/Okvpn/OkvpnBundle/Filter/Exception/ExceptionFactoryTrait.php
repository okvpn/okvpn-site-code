<?php

namespace Okvpn\OkvpnBundle\Filter\Exception;

trait ExceptionFactoryTrait
{
    /** @var  ExceptionFactory */
    protected $exceptionFactory;

    /**
     * @return ExceptionFactory
     */
    protected function getExceptionFactory()
    {
        if (!$this->exceptionFactory) {
            $this->exceptionFactory = new ExceptionFactory();
        }
        return $this->exceptionFactory;
    }
}
