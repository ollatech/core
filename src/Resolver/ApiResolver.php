<?php

namespace Olla\Core\Resolver;


class ApiResolver extends Resolver
{

    public function middleware($operation) {
       
    }
    public function operation(string $operationId) {
        
        $args = [];
        $operation =  $this->metadata->api($operationId);
        if(null === $operation) {
            return;
        }
        return $operation;
    }
}
