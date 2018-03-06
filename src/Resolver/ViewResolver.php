<?php
namespace Olla\Core\Resolver;


class ViewResolver extends BaseResolver
{
	public function resolve(array $args = [], $request) {
        if(!$this->firewall->canAccess($args['operation_id'])) {
            throw new Exception("Access Denied", 1);
        }
        $props = [];
        if(null !== $operation = $this->operation($args['carrier'], $args['operation_id'])) {
            if(null === $controllerId = $operation->getController()) {
                return;
            }
            $controller = $this->service($controllerId);
            if (is_callable($controller))
            {
                $result = call_user_func_array($controller, [$operation, $request]);
                if($result instanceof View) {
                    return $result;
                }
                if(!is_array($result)) {
                    throw new \Exception(sprintf("%s Should return an array", $controllerId));
                }
                $props = array_merge($props, $result);
            }
        } 

        switch ($args['format']) {
            case 'html':
            return $this->view($operation, $args, $props);
            break;
            default:
            return $this->converter->render($args, $props);
            break;
        }
    }
   
  
}
