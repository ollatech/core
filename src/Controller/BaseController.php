<?php
namespace Olla\Core\Controller;

use Olla\Core\Resolver\ResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseController
{
    protected $requestStack;
    protected $resolver;
    public function __construct(RequestStack $requestStack, ResolverInterface $resolver) {
        $this->requestStack = $requestStack;
        $this->resolver = $resolver;
    }

    protected function args() {
        $request = $this->getRequest();
        $args = [];
        if(null !== $resolverId = $request->attributes->get('_operation')) {
            $args['resolver_id'] = $resolverId;
        }
        if(null !== $carrier = $request->attributes->get('_carrier')) {
            $args['carrier'] = $carrier;
        }
        if(null !== $format = $request->attributes->get('_format')) {
            $args['format'] = $format;
        }
        if(null !== $parameters = $request->attributes->get('_parameters')) {
            $args['parameters'] = $parameters;
        }
        return $args;
    }
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
