<?php defined('SYSPATH') OR die('No direct script access.');
class HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {
 
    /**
     * Generate a Response for the 401 Exception.
     * The user should be shown a nice 401 page.
     * @return Response
     */
    public function get_response()
    {
        return Response::factory()->headers('Location', URL::base());
    }
}