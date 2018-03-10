<?php
namespace Olla\Core\Bundle;

use Olla\Core\Bundle\Compiler\ResolverCompilerPass;
use Olla\Core\Bundle\Compiler\MiddlewareCompilerPass;
use Olla\Core\Bundle\Compiler\NegotiationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OllaCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->addCompilerPass(new NegotiationCompilerPass());
		$container->addCompilerPass(new MiddlewareCompilerPass());
		$container->addCompilerPass(new ResolverCompilerPass());
		
		
	}
}
