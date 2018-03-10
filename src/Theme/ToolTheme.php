<?php
namespace Olla\Core\Theme;
use Olla\Theme\Theme as ViewTheme;
class ToolTheme implements ThemeInterface {
	protected $view;
	protected $themeName;

	public function __construct(ViewTheme $view, string $themeName = null) {
		$this->view = $view;
		$this->themeName = $themeName;
	}

	public function render($template, $props, $assets, $react, $options, $context) {
		return $this->view->setTheme($this->themeName)->render($template, $props, $assets, $react, $options, $context);
	}
}