<?php

namespace Core\Configuration;

use Core\Service\ServiceContainer;
use Core\Service\Service;
use Core\Service\Argument;

class ServiceContainerSection implements IConfigurationSection{
    
    public function execute(Configuration $configuration, \SimpleXMLElement $xml){
        
        $serviceContainer = new ServiceContainer();

        foreach($xml->services->service as $serv){
            $service = new Service($serv->class);
            
            foreach($serv->constructorArg as $arg){
                $argument = new Argument();

                if(isset($arg['type']) && $arg['type'] == 'property'){
                    $arg = $configuration->get('settings')->path((string)$arg);
                }
                if(isset($arg['type']) && $arg['type'] == 'ref'){
                    $argument->setIsReference(true);
                }
                
                $argument->setValue((string)$arg);
                $service->addConstructorArg($argument);
            }
            
            $serviceContainer->add((string)$serv['name'], $service);
        }
        
        $configuration->add('serviceContainer', $serviceContainer);
    }
}

