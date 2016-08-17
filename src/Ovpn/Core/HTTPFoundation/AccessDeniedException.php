<?php

namespace Ovpn\Core\HTTPFoundation;


class AccessDeniedException extends \HTTP_Exception_401
{
    public function get_response()
    {
        return \Response::factory()->headers('Location', \URL::base());
    }
}