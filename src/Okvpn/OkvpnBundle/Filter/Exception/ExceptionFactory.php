<?php

namespace Okvpn\OkvpnBundle\Filter\Exception;

class ExceptionFactory
{
    /**
     * @param array $messages
     * @return UserException
     */
    public function createUserException(array $messages)
    {
        $exception = new UserException();
        $exception->setAjaxMessages($messages);
        return $exception;
    }
}
