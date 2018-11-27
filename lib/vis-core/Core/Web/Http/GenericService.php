<?php

namespace Core\Web\Http;

use Core\Web\Configuration;
use Core\Service\ServiceContainer;

abstract class GenericService{
    
    private $config = null;
    
    public function __construct(Configuration $config){
        $this->config = $config;
    }
    
    public function getSettings(string $path = ''){
        return $this->config->getSettings($path);
    }
    
    public function getServiceContainer() : ServiceContainer{
        return $this->config->getServiceContainer();
    }

    public abstract function service(HttpContext $httpContext);
    
    public function __get($name) {
        return $this->config->getServiceContainer()->get($name);
    }
}