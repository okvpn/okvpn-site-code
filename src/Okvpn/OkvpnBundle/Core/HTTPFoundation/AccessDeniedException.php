<?php

namespace Okvpn\OkvpnBundle\Core\HTTPFoundation;

class AccessDeniedException extends \HTTP_Exception_401
{

    public function get_response() // @codingStandardsIgnoreLine
    {
        return \Response::factory()->headers('Location', \URL::base());
    }
}
