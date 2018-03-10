<?php

namespace Olla\Core\Bundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;

final class MiddlewareCompilerPass implements CompilerPassInterface
{
	use PriorityTaggedServiceTrait;
	public function process(ContainerBuilder $container)
	{ 
		$middlewares  = ['api', 'admin', 'frontend', 'tool'];
		foreach ($middlewares as $middleware) {
			$serviceId = 'olla.'.$middleware.'_middleware';
			if ($container->hasDefinition($serviceId)) {
				$service = $container->findDefinition($serviceId);
				$middlewares = $this->findAndSortTaggedServices("olla.{$middleware}_middleware", $container);
				$service->addArgument($middlewares);
			}
		}
	}
}