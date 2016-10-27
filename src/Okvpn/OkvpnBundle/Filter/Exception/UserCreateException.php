<?php

namespace Okvpn\OkvpnBundle\Filter\Exception;

class UserCreateException extends \Exception
{
    /** @var  array */
    private $messages;

    /**
     * @param array $messages
     */
    public function setValidateMessages(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getValidateMessages()
    {
        return $this->messages;
    }
}
