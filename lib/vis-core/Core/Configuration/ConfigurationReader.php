<?php

namespace Core\Configuration;

class ConfigurationReader{
    
    private $configuration = null;
    
    public function __construct(\SimpleXMLElement $xml){
        
        $this->configuration = new Configuration();
        
        $x = new RouteSection($this->configuration);
        $x->execute($xml);
        
        $x1 = new SettingsSection($this->configuration);
        $x1->execute($xml);
        
        $x2 = new ServiceContainerSection($this->configuration);
        $x2->execute($xml);
        
        $x3 = new ExceptionHandlerSection($this->configuration);
        $x3->execute($xml);
    }
    
    public function getConfiguration(){
        return $this->configuration;
    }
}
