<?php

namespace Olla\Core\Resolver;


class ToolResolver extends Resolver
{

    public function middleware($operation) {
       
    }
    public function operation(string $operationId) {
        $args = [];
        $operation =  $this->metadata->tool($operationId);
        if(null === $operation) {
            return;
        }
        return $operation;
    }
}
