<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_Exception extends Kohana_Kohana_Exception {

    /**
     * Generate a Response for all Exceptions without a more specific override
     *
     * The user should see a nice error page, however, if we are in development
     * mode we should show the normal Kohana error page.
     *
     * @return Response
     */
    public static function response(Exception $e)
    {
        if (Kohana::$environment >= Kohana::DEVELOPMENT) {
            return parent::response($e);
        }
        $view = View::factory('error/500');
      
        $response = Response::factory()
            ->status(500)
            ->body($view->render());
        return $response;
    }
}