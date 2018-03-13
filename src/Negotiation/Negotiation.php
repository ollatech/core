<?php
namespace Olla\Core\Negotiation;

class Negotiation implements NegotiationInterface {
    
	protected $providers;

	public function __construct(array $providers)
	{
		$this->providers = $providers;
	}

    public function output(string $format, array $props = [])
    {
    	foreach ($this->providers as $provider) {
    		try {
    			if ($provider instanceof NegotiationInterface && !$provider->supports($format)) {
    				continue;
    			}
    			return $provider->output($format, $props);
    		} catch (\Exception $e) {
    			continue;
    		}
    	}
        return new \Exception("Can't handle this format, please check with developer");
    }
}