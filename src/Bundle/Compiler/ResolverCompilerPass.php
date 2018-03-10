<?php

namespace Olla\Core\Bundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class ResolverCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{ 
		$resolvers  = ['api', 'admin', 'frontend', 'tool'];
		foreach ($resolvers as $resolver) {
			$serviceId = 'olla.'.$resolver.'_resolver';
			if ($container->hasDefinition($serviceId)) {
				$service = $container->findDefinition($serviceId);

				$middlewareId = $container->getParameter('olla.'.$resolver.'_middleware');

				if ($container->hasDefinition($middlewareId)) {
					$service->replaceArgument(2, new Reference($middlewareId));
				}

				$negotiationId = $container->getParameter('olla.'.$resolver.'_negotiation');
				if ($container->hasDefinition($negotiationId)) {
					$service->replaceArgument(3, new Reference($negotiationId));
				}
				$themeId = $container->getParameter('olla.'.$resolver.'_theme');
				if ($container->hasDefinition($themeId)) {
					$service->replaceArgument(4, new Reference($themeId));
				}
			}
		}
	}
}
