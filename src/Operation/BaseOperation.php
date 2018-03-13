<?php
namespace Olla\Core\Operation;

abstract class  BaseOperation 
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

	protected function operation() {
		return $this->operation;
	}

}
