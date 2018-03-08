<?php
namespace Olla\Core\Controller;

use Olla\Core\Resolver\ResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminController extends BaseController
{
    
    public function __invoke() {
        $request = $this->getRequest();
        if(null === $resolverId = $request->attributes->get('_operation')) {
            throw new Exception("Error Processing Request", 1);
        }
        if(null === $carrier = $request->attributes->get('_carrier')) {
            throw new Exception("Error Processing Request", 1);
        }
        $args = $this->args();
        return $this->resolver->resolve($args, $request);
    }
}
