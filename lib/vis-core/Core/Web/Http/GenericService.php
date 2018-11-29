<?php

namespace Core\Web\Http;

use Core\Configuration\Configuration;

abstract class GenericService{
    
    private $config = null;
    
    public function __construct(Configuration $config){
        $this->config = $config;
    }
    
    public function getConfiguration(){
        return $this->config;
    }
    
    public abstract function service(HttpContext $httpContext);
    
    public function __get($name) {
        return $this->getConfiguration()->getServiceContainer()->get($name);
    }
}