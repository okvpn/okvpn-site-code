<?php
class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 
{

    /**
     * @return mixed
     * @throws \Kohana_Exception
     * @throws \View_Exception
     */
    public function get_response()
    {
        $view = \View::factory('error/404');
        $view->message = $this->getMessage();

        return \Response::factory()->status(404)->body($view->render());
    }
}