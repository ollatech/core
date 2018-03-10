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

     public function __invoke() {
        $request = $this->getRequest();
        if(null === $operationId = $request->attributes->get('_operation')) {
            throw new Exception("Error Processing Request", 1);
        }
        if(null === $format = $request->attributes->get('_format')) {
            throw new Exception("Error Processing Request", 1);
        }
        $args = $this->args();
        return $this->resolver->http($operationId, $format, $request);
    }

    protected function args() {
        $request = $this->getRequest();
        $args = [];
        if(null !== $resolverId = $request->attributes->get('_operation')) {
            $args['operation_id'] = $resolverId;
        }
        if(null !== $format = $request->attributes->get('_format')) {
            $args['format'] = $format;
        }
        return $args;
    }
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
