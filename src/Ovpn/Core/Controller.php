<?php

namespace Ovpn\Core;

use Annotations\DependencyInjectionAnnotation as DI;
use Ovpn\Core\HTTPFoundation\NotFoundException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Controller decorate Kohana_Controller
 */
abstract class Controller implements ContainerAwareInterface, ControllerInterface
{
    
    use ContainerAwareTrait;

    /**
     * @DI(service="container")
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Controller
     */
    protected $kohanaController;
    
    
    public function __construct(ControllerInterface $controller)
    {
        $this->kohanaController = $controller;
    }

    /**
     * @return \Request
     */
    public function getRequest()
    {
        return $this->kohanaController->request;
    }

    /**
     * @return \Response
     */
    public function getResponse()
    {
        return $this->kohanaController->response;
    }
    
    public function setJsonResponse(array $data)
    {
        $this->getResponse()->headers('Content-type', 'application/json');
        $this->getResponse()->body(json_encode($data));
    }
    
    /**
     * 
     * Issues a HTTP redirect.
     *
     * Proxies to the [HTTP::redirect] method.
     *
     * @param  string  $url   URI to redirect to
     * @param  int     $code  HTTP Status code to use for the redirect
     * @throws \HTTP_Exception
     */
    public function redirect($url = '', $code = 302) 
    {
        return $this->kohanaController->redirect($url, $code);
    }
    

    /**
     * Checks the browser cache to see the response needs to be returned,
     * execution will halt and a 304 Not Modified will be sent if the
     * browser cache is up to date.
     *
     *     $this->check_cache(sha1($content));
     *
     * @param  string  $etag  Resource Etag
     * @return \Response
     */
    public function check_cache($etag = null)
    {
        return \HTTP::check_cache($this->getRequest(), $this->getResponse(), $etag);
    }
    

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->before();
        $action = $this->getRequest()->action() . 'Action';

        $reflect = new \ReflectionClass($this);

        if (! $reflect->hasMethod($action)) {
            throw new NotFoundException();
        }

        $reflect->getMethod($action)
            ->invoke($this);

        // Execute the "after action" method
        $this->after();
        
        return $this->getResponse();
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @inheritdoc
     */
    public function before() {}

    /**
     * @inheritdoc
     */
    public function after() {}


}