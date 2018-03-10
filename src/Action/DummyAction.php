<?php

namespace Olla\Core\Action;

use Olla\Core\Operation\MultiOperation;
use Olla\Core\Operation\ViewOperation;
use Olla\Core\Operation\ApiOperation;
use Symfony\Component\HttpFoundation\Request;

final class DummyAction extends MultiOperation
{
	public function http(Request $request) {
		return [];
	}
	public function graph(Request $request) {
		return [];
	}
}
