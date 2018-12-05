<?php

namespace Core\Web\Http;

use Core\Configuration\ConfigurationManager;

abstract class GenericService{
    
    private $configManager = null;
    
    public function __construct(ConfigurationManager $configManager){
        $this->configManager = $configManager;
    }
    
    public function getConfigurationManager() : ConfigurationManager{
        return $this->configManager;
    }
    
    public function getConfiguration(){
        return $this->configManager->getConfiguration();
    }
    
    public abstract function service(HttpContext $httpContext);
    
    public function __get($name) {
        return $this->getConfiguration()->getServiceContainer()->get($name);
    }
}