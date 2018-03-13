<?php
namespace Olla\Core\Operation;



abstract class  MultiOperation 
{
    protected $format;
    protected $theme;
    protected $operation;
    protected $negotiation;
    protected $operation_type;

    public function setOperationType($operation_type) {
        $this->operation_type = $operation_type;
        return $this;
    }

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

    protected function output(array $props = [], string $format = null) {
        return $this->negotiation->output($this->format, $props);
    }
}