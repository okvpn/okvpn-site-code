<?php

use Kernel\CumulativeResourceManager;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Annotations\AnnotationReader;
use Annotations\DependencyInjectionAnnotationInterface;
use Ovpn\Core\KohanaController;

class Request_Client_Internal extends Kohana_Request_Client_Internal
{

    /**
     * @inheritdoc
     */
    public function execute_request(Request $request, Response $response)
    {
        // Create the class suffix
        $suffix = 'Controller';

        // Controller
        $controller = $request->controller();

        /*$container = $this->getContainer();
        $um = $container->get('ovpn_user.manager');*/

        $classController = null;
        foreach ($this->getBundlesName() as $bundleName) {
            if (class_exists($bundleName . '\\Controller\\' . $controller . $suffix)) {
                $classController = $bundleName . '\\Controller\\' . $controller . $suffix;
                break;
            }
        }

        if (Kohana::$profiling) {
            // Set the benchmark name
            $benchmark = '"'.$request->uri().'"';

            if ($request !== Request::$initial and Request::$current) {
                // Add the parent request uri
                $benchmark .= ' « "'.Request::$current->uri().'"';
            }

            // Start benchmarking
            $benchmark = Profiler::start('Requests', $benchmark);
        }

        // Store the currently active request
        $previous = Request::$current;

        // Change the current request to this request
        Request::$current = $request;

        // Is this the initial request
        //$initial_request = ($request === Request::$initial);

        try {
            if ( !$classController) {

                throw HTTP_Exception::factory(404,
                    'The requested URL :uri was not found on this server.',
                    array(':uri' => $request->uri())
                )->request($request);
            }
            

            // Load the controller using reflection
            $class = new ReflectionClass($classController);
            $controller = $class->newInstance(new KohanaController($request, $response));

            $reader = new AnnotationReader();

            foreach ($class->getProperties() as $property) {

                /** @var DependencyInjectionAnnotationInterface $propertyInjection */
                $propertyInjection = $reader
                    ->getPropertyAnnotation($property, 'Annotations\\DependencyInjectionAnnotationInterface');

                if ($propertyInjection instanceof DependencyInjectionAnnotationInterface) {
                    $setterName = 'set' . ucfirst($property->getName());

                    $class
                        ->getMethod($setterName)
                        ->invokeArgs($controller, [$this->getContainer()->get($propertyInjection->getServiceName())]);
                    
                }
            }
            


            if ($class->isAbstract()) {
                throw new Kohana_Exception(
                    'Cannot create instances of abstract :controller',
                    array(':controller' => $classController)
                );
            }


            // Run the controller's execute() method
            $response = $class->getMethod('execute')->invoke($controller);

            if ( ! $response instanceof Response) {
                // Controller failed to return a Response.
                throw new Kohana_Exception('Controller failed to return a Response');
            }
        }

        catch (HTTP_Exception $e) {
            // Store the request context in the Exception
            if ($e->request() === NULL) {
                $e->request($request);
            }

            // Get the response via the Exception
            $response = $e->get_response();
        }

        catch (Exception $e) {
            // Generate an appropriate Response object
            $response = Kohana_Exception::_handler($e);
        }

        // Restore the previous request
        Request::$current = $previous;

        if (isset($benchmark)) {
            // Stop the benchmark
            Profiler::stop($benchmark);
        }

        // Return the response
        return $response;
    }

    /**
     * @return Container
     */
    private function getContainer()
    {
        return CumulativeResourceManager::getInstance()->getContainer();
    }

    /**
     * @return array
     */
    private function getBundlesName()
    {
        $names = [];
        foreach (CumulativeResourceManager::getInstance()->getBundles() as $bundle) {
            $names[] = $bundle->getName();
        }

        return $names;
    }
}