<?php
namespace Olla\Core\Bundle;

use Olla\Core\Bundle\Compiler\ViewCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OllaCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->addCompilerPass(new ViewCompilerPass());
	}
}
