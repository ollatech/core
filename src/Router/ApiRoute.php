<?php
namespace Olla\Core\Router;

use Olla\Prisma\Metadata;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\Loader;
final class ApiRoute extends Loader
{
	private $metadata;
	private $controller;
	private $format;
	private $carrier;
	public function __construct(Metadata $metadata, string $controller = null, string $carrier = null, string $format = null)
	{
		$this->metadata = $metadata;
		$this->controller = $controller;
		$this->carrier = $carrier;
		$this->format = $format;
	}

	/**
     * {@inheritdoc}
     */
	public function load($data, $type = null): RouteCollection
	{
		$operations = $this->metadata->operations();
		$routeCollection = new RouteCollection();
		foreach ($operations as $opId => $op) {
			$route = [];
			$route['_controller'] = $this->controller;
			$route['_operation'] = $op->getId();
			$route['_carrier'] = $this->carrier;
			$route['_format'] = $this->format;
			$routeCollection->add($opId, new Route($op->getPath(), $route+$op->getRoute()+[],
				[],
				[],
				'',
				[],
				$op->getMethods(),
				''));
		}
		return $routeCollection;
	}

	public function supports($resource, $type = null)
	{
		return 'olla_api' === $type;
	}	
}
