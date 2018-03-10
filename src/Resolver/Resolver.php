<?php
namespace Olla\Core\Resolver;

use Olla\Prisma\MetadataInterface;
use Olla\Core\Theme\ThemeInterface;
use Olla\Core\Negotiation\NegotiationInterface;
use Olla\Core\Middleware\MiddlewareInterface;
use Olla\Core\Operation\MultiOperation;
use Olla\Core\Operation\ViewOperation;
use Olla\Core\Operation\ApiOperation;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class  Resolver implements ResolverInterface
{

    protected $container;
    protected $metatada;
    protected $negotiation;
    protected $middleware;
    protected $theme;

    public function __construct(
        ContainerInterface $container, 
        MetadataInterface $metadata, 
        MiddlewareInterface $middleware = null, 
        NegotiationInterface $negotiation = null, 
        ThemeInterface $theme = null
    ) {
        $this->container = $container;
        $this->metadata = $metadata;
        $this->middleware = $middleware;
        $this->negotiation = $negotiation;
        $this->theme = $theme;
    }

    public function http(string $operationId, string $format = null, $request) {
        
        $props = [];
        if(null !== $operation = $this->operation($operationId)) {
            if(null === $controllerId = $operation->getController()) {
                return;
            }
            $controller = $this->service($controllerId);
            if($controller instanceof MultiOperation) {
                return $controller
                ->setTheme($this->theme)
                ->setNegotiator($this->negotiation)
                ->setFormat($format)
                ->setOperation($operation)
                ->http($request);
            }
            if($controller instanceof ApiOperation) {
                return $controller
                ->setNegotiator($this->negotiation)
                ->setFormat($format)
                ->setOperation($operation)
                ->http($request);
            }
            if($controller instanceof ViewOperation) {
                return $controller
                ->setTheme($this->theme)
                ->setOperation($operation)
                ->http($request);
            }
            return $controller->http($request);
        } 
        throw new \Exception("Cant find any operation", 1);
    }
    
   public function graph(string $operationId, string $format = null, array $args = []) {
        $props = [];
        if(null !== $operation = $this->operation($operationId)) {
            if(null === $controllerId = $operation->getController()) {
                return;
            }
            $controller = $this->service($controllerId);
            if($controller instanceof MultiOperation) {
                return $controller
                ->setTheme($this->theme)
                ->setNegotiator($this->negotiation)
                ->setFormat($format)
                ->setOperation($operation)
                ->graph($args);
            }
            if($controller instanceof ApiOperation) {
                return $controller
                ->setNegotiator($this->negotiation)
                ->setFormat($format)
                ->setOperation($operation)
                ->graph($args);
            }
            if($controller instanceof ViewOperation) {
                return $controller
                ->setTheme($this->theme)
                ->setOperation($operation)
                ->graph($args);
            }
            return $controller->graph($args);
        } 
        throw new \Exception("Cant find any operation", 1);
    }
    protected function service(string $serviceId) {
        if ($this->container->has($serviceId))
        {
            return $this->container->get($serviceId);
        } 
        throw new \Exception(sprintf("%s not exist on service", $serviceId));
    }
    abstract function middleware($operation);
    abstract function operation(string $operationId);
}
