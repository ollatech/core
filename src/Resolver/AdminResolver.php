<?php

namespace Olla\Core\Resolver;


class AdminResolver extends Resolver
{

    public function middleware($operation) {
       
    }
    public function operation(string $operationId) {
        $args = [];
        $operation =  $this->metadata->admin($operationId);
        if(null === $operation) {
            return;
        }
        return $operation;
    }
}
