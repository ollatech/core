<?php

namespace Olla\Core\Bundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;

final class NegotiationCompilerPass implements CompilerPassInterface
{
	use PriorityTaggedServiceTrait;

	public function process(ContainerBuilder $container)
	{ 
		$negotiations  = ['api', 'admin', 'frontend', 'tool'];
		foreach ($negotiations as $negotiation) {
			$serviceId = 'olla.'.$negotiation.'_negotiation';
			if ($container->hasDefinition($serviceId)) {
				$service = $container->findDefinition($serviceId);
				$negotiators = $this->findAndSortTaggedServices("olla.{$negotiation}_negotiation", $container);
				$service->addArgument($negotiators);
			}
		}
	}
}