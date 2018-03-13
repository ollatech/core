<?php
namespace Olla\Core\Operation;



abstract class  ViewOperation 
{
    protected $theme;

    protected $operation;

    protected $operation_type;

    public function setOperationType($operation_type) {
        $this->operation_type = $operation_type;
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

	protected function view(string $template, array $props = []) {
        $template = $this->operation->getTemplate();
        $assets = $this->operation->getAssets();
        $react = $this->operation->getReact();
        $options = $this->operation->getOptions();
        $context = [
            'operation_type' => $this->operation_type,
            'operation' => $this->operation->getId(),
            'resource' => $this->operation->getResource(),
            'action' => $this->operation->getAction()
        ];
        return $this->theme->render($template, $props, $assets, $react, $options, $context);
    }
}