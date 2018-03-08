<?php

namespace Olla\Core\Operations\Admin;


use Symfony\Component\HttpFoundation\Request;

final class WelcomeAction
{
	public function __construct() {
	
	}

	public function __invoke($operation, Request $request) {
		return [];
	}
}
