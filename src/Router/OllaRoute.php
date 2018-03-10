<?php
namespace Olla\Core\Router;

use Olla\Prisma\Metadata;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\Loader;

final class OllaRoute extends Loader
{
	private $metadata;
	private $controllers;
	private $formats;
	private $prefixes;
	private $themes;

	public function __construct(Metadata $metadata, array $controllers = [], array $formats = [], array $prefixes = [], array $themes = [])
	{
		$this->metadata = $metadata;
		$this->controllers = $controllers;
		$this->formats = $formats;
		$this->prefixes =  $prefixes;
		$this->themes = $themes;
	}
	/**
     * {@inheritdoc}
     */
	public function load($data, $type = null): RouteCollection
	{
		$routeCollection = new RouteCollection();
		$operations = $this->metadata->admins();
		foreach ($operations as $opId => $op) {
			$this->buildRoute('admin', $routeCollection, $op);
		}
		$operations = $this->metadata->apis();
		foreach ($operations as $opId => $op) {
			$this->buildRoute('api', $routeCollection, $op);
		}
		$operations = $this->metadata->frontends();
		foreach ($operations as $opId => $op) {
			$this->buildRoute('frontend', $routeCollection, $op);
		}
		$operations = $this->metadata->tools();
		foreach ($operations as $opId => $op) {
			$this->buildRoute('tool', $routeCollection, $op);
		}
		return $routeCollection;
	}

	private function buildRoute(string $carrier, $routeCollection, $op) {
		$route = [];
		$route['_controller'] = $this->controllers[$carrier];
		$route['_operation'] = $op->getId();
		$route['_carrier'] = $carrier;
		$route['_format'] = $this->formats[$carrier];
		$route['_theme'] = $this->setTheme($carrier);
		$path = $this->prefixes[$carrier].$op->getPath();
		$routeCollection->add($op->getId(), new Route($path, $route+$op->getRoute()+[],
			[],
			[],
			'',
			[],
			$op->getMethods(),
			''));
	}

	private function setTheme($carrier) {
		if(isset($this->themes[$carrier])) {
			return $this->themes[$carrier];
		}
	}
	
	public function supports($resource, $type = null)
	{
		return 'olla' === $type;
	}	
}
