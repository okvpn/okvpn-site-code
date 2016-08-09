<?php defined('SYSPATH') OR die('No direct script access.');
class HTTP_Exception_500 extends Kohana_HTTP_Exception_500 {
 
    public function get_response()
    {
        $view = View::factory('error/500');
        $view->message = $this->getMessage();
        $response = Response::factory()
            ->status(500)
            ->body($view->render());
        return $response;
    }
}