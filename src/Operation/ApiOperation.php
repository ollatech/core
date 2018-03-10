<?php
namespace Olla\Core\Operation;

abstract class  ApiOperation 
{
	protected $format;
	protected $theme;
	protected $operation;
	protected $negotiation;

	public function setFormat($format) {
		$this->format = $format;
		return $this;
	}

	public function setTheme($theme) {
		$this->theme = $theme;
		return $this;
	}
	public function setOperation($operation) {
		$this->operation = $operation;
		return $this;
	}
	public function setNegotiation($negotiation) {
		$this->negotiation = $negotiation;
		return $this;
	}

	protected function output(array $props = [], string $format = null) {
		return $this->negotiation->output($this->format, $props);
	}
}
