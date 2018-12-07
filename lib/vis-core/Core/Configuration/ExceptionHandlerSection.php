<?php

namespace Core\Configuration;

class ExceptionHandlerSection implements IConfigurationSection{
    
    public function execute(Configuration $configuration, \SimpleXMLElement $xml){

        $exceptionHandlers = [];
        
        foreach($xml->exceptionHandlers->handler as $errorHandler){ 
            $exceptionHandlers[] = (object)['exception' => (string)$errorHandler->exception, 'class' => (string)$errorHandler->class];
        }
        
        $configuration->add('exceptionHandlers', $exceptionHandlers);
        unset($exceptionHandlers);
    }
}

