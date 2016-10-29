<?php

namespace Okvpn\OkvpnBundle\Filter\Exception;

class UserException extends \Exception
{
    /** @var  array */
    private $messages;

    /**
     * @param array $messages
     */
    public function setAjaxMessages(array $messages)
    {
        $messages['error'] = true;
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getAjaxMessages()
    {
        return $this->messages;
    }
}
