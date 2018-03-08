<?php
namespace Olla\Core\Resolver;

use Olla\Prisma\Metadata;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class  BaseResolver implements ResolverInterface
{

    protected $container;
    protected $metatada;

    public function __construct(ContainerInterface $container, Metadata $metadata) {
        $this->container = $container;
        $this->metadata = $metadata;
    }

 
    protected function view($operation, $args, $props) {
        $template = $operation->getTemplate();
        $assets = $operation->getAssets();
        $react = $operation->getReact();
        $options = $operation->getOptions();
        $context = [
            'resource' => $operation->getResource(),
            'action' => $operation->getAction()
        ];
        return $this->theme->render($template, $props, $assets, $react, $options, $context);
    }
    protected function operation(string $carrier, string $operationId) {
        $args = [];
        $operation = null;
        switch ($carrier) {
            case 'restapi':
            $operation = $this->metadata->api($operationId);
            break;
            case 'tool':
            $operation = $this->metadata->tool($operationId);
            break;
            case 'frontend':
            $operation = $this->metadata->frontend($operationId);
            break;
            case 'admin':
            $operation = $this->metadata->admin($operationId);
            break;
            default:
            break;
        }
        if(null === $operation) {
            return;
        }
        return $operation;
    }

    protected function service(string $serviceId) {
        if ($this->container->has($serviceId))
        {
            return $this->container->get($serviceId);
        } 
        throw new \Exception(sprintf("%s not exist on service", $serviceId));
    }
}
