<?php
namespace Olla\Core\Resolver;


use Olla\Prisma\Metadata;
use Olla\Theme\ThemeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class FrontendResolver extends BaseResolver
{
    protected $container;
    protected $metatada;
    protected $theme;

    public function __construct(ContainerInterface $container, Metadata $metadata, ThemeInterface $theme) {
        $this->container = $container;
        $this->metadata = $metadata;
        $this->theme = $theme;
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
            return $this->view($operation, $args, $props);
        } 
        throw new \Exception("Cant find any operation", 1);
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
}
