<?php 
namespace Ovpn\Controller;

use Annotations\DependencyInjectionAnnotation as DI;
use Ovpn\Security\SecurityFacade;

class TestController extends \Controller
{
    /**
     * @var SecurityFacade
     * @DI(service="ovpn_security")
     */
    protected $securityContext;

    public function action_index()
    {
        var_dump($this->securityContext->doLogin('tsykun314@gmail.com', 'php12346'));
    }

    public function setSecurityContext(SecurityFacade $security)
    {
        $this->securityContext = $security;
    }
}