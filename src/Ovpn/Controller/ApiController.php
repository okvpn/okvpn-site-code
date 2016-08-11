<?php

namespace Ovpn\Controller;

use URL;
use Kohana;
use View;
use ORM;

class ApiController extends \Controller
{

    public function action_proxy()
    {
        // TODO: cound not use static factory
        $data = ORM::factory('Proxy')->getProxies();
        $format = $this->request->param('format');

        if ($format == 'xml') {
            $xml = Okvpn::arrayToXML($data);
            $this->response->headers('Content-type','application/xml')
                ->body($xml->asXML());

        } elseif ($format == 'raw') {
            $buff = '';
            foreach($data as $val) {
                if (isset($val['ip'])) {
                    $buff.= $val['ip'].PHP_EOL;
                } else {
                    $buff.=$val;
                }
            }

            $this->response->headers('Content-type','text/plain')
                ->body($buff);

        } else {
            $this->response->headers('Content-type','application/json')
                ->body(json_encode($data));         
        }
    }

    private function Response($data, $format) 
    {
        switch ($format) {
            case 'raw':
                $this->response->headers('Content-type','text/plain')
                    ->body($data);
                break;
            case 'xml':
                
                $this->response->headers('Content-type','application/xml')
                    ->body($data);
                break;
            default:
                $this->response->headers('Content-type','application/json')
                    ->body($data);
                break;
        }
    }

}
