<?php
namespace Olla\Core\Resolver;

use Olla\Prisma\Metadata;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ToolResolver extends BaseResolver
{
 
    protected $container;
    protected $metatada;

    public function __construct(ContainerInterface $container, Metadata $metadata) {
        $this->container = $container;
        $this->metadata = $metadata;
    }
	public function resolve(array $args = [], $request) {
        $props = [];
        if(null !== $operation = $this->operation($args['carrier'], $args['operation_id'])) {
            if(null === $controllerId = $operation->getController()) {
                return;
            }
            $controller = $this->service($controllerId);
            if (is_callable($controller))
            {
                $result = call_user_func_array($controller, [$request]);
                if($result instanceof View) {
                    return $result;
                }
                if(!is_array($result)) {
                    throw new \Exception(sprintf("%s Should return an array", $controllerId));
                }
                $props = array_merge($props, $result);
            }
        } 
        return $this->render($args['format'], $props);
    }

    public function render(string $format, array $data) {
        $response = [];
        if(is_array($data)) {
            foreach ($data as $key => $object) {
                $serialized = $this->serializer->serialize($object, $format, $options);
                $response[] = $this->serializer->decode($serialized, $format);
            }
        } else {
            $response = $this->serializer->serialize($data, $format, $options);
        }
        return new JsonResponse($response);
    }
}
