<?php

namespace Olla\Core\Operations\Api;


use Symfony\Component\HttpFoundation\Request;

final class CollectionAction
{
	public function __construct() {
	
	}
	public function __invoke(Request $request) {
		return [];
	}
}
