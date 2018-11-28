<?php

namespace Core\Configuration;

use Core\Service\ServiceContainer;
use Core\Service\Service;
use Core\Service\Argument;

class ServiceContainerSection extends ConfigurationSection{
    
    public function execute($xml){
        
        $serviceContainer = new ServiceContainer();
        
        foreach($xml->services->service as $serv){
            $service = new Service($serv->class);
            
            foreach($serv->constructorArg as $arg){
                $argument = new Argument();
               
                if(isset($arg['type']) && $arg['type'] == 'property'){
 
                    $arg = $this->getSection('settings')->path((string)$arg);
                }
                if(isset($arg['type']) && $arg['type'] == 'ref'){
                    $argument->setIsReference(true);
                }
                
                $argument->setValue((string)$arg);

                $service->addConstructorArg($argument);
            }
            
            $serviceContainer->add((string)$serv['name'], $service);
        }
        
        $this->addSection('serviceContainer', $serviceContainer);
    }
}

