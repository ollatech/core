<?php
namespace Olla\Core\Router;

use Olla\Prisma\Metadata;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\Loader;


class BaseRoute extends Loader
{
	private $metadata;
	private $controller;
	private $format;
	private $carrier;
	public function __construct(Metadata $metadata, string $controller = null, string $format = null, string $carrier = null)
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
		$operations = $this->metadata->admins();
		$routeCollection = new RouteCollection();
		foreach ($operations as $opId => $op) {
			$op_route = $op->getRoute();
			$path = isset($op_route['path']) ? $op_route['path'].'.{_format}': '/';
			$method = isset($op_route['method']) ? $op_route['method']: null;
			$route = [];
			$route['_controller'] = $this->controller;
			$route['_operation'] = $op->getId();
			$route['_carrier'] = $this->carrier;
			$route['_format'] = $this->format;
			$routeCollection->add($opId, new Route($path, $route+[],
				[],
				[],
				'',
				[],
				$method,
				''));
		}
		return $routeCollection;
	}
	public function supports($resource, $type = null)
	{
		return;
	}
	
}
