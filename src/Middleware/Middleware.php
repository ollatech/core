<?php
namespace Olla\Core\Middleware;

class Middleware implements MiddlewareInterface {
    
	private $providers;
	public function __construct(array $providers)
	{
		$this->providers = $providers;
	}
    public function check(string $operationId = null)
    {
    	foreach ($this->providers as $provider) {
    		try {
    			if ($provider instanceof MiddlewareInterface && !$provider->supports($operationId)) {
    				continue;
    			}
    			return $provider->check($operationId);
    		} catch (\Exception $e) {
    			continue;
    		}
    	}
    }
}