<?php
namespace Olla\Core\Operation;



abstract class  ViewOperation 
{
    protected $theme;

    protected $operation;

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
            'resource' => $this->operation->getResource(),
            'action' => $this->operation->getAction()
        ];
        return $this->theme->render($template, $props, $assets, $react, $options, $context);
    }
}