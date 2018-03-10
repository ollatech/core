<?php

namespace Olla\Core\Resolver;




class FrontendResolver extends Resolver
{

    public function middleware($operation) {
       
    }
    public function operation(string $operationId) {
        $args = [];
        $operation =  $this->metadata->frontend($operationId);
        if(null === $operation) {
            return;
        }
        return $operation;
    }
}
