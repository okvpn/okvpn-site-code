<?php defined('SYSPATH') OR die('No direct script access.');
class HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {
 
    /**
     * Generate a Response for the 404 Exception.
     *
     * The user should be shown a nice 404 page.
     * 
     * @return Response
     */
    public function get_response()
    {
        $view = View::factory('error/401');
        $view->message = $this->getMessage();
        $response = Response::factory()
            ->status(401)
            ->body($view->render());
        return $response;
    }
}