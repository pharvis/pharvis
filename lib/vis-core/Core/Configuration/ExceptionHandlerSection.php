<?php

namespace Core\Configuration;

class ExceptionHandlerSection extends ConfigurationSection{
    
    public function execute(\SimpleXMLElement $xml){

        $exceptionHandlers = [];
        
        foreach($xml->exceptionHandlers->handler as $errorHandler){ 
            $exceptionHandlers[] = (object)['exception' => (string)$errorHandler->exception, 'class' => (string)$errorHandler->class];
        }
        
        $this->addSection('exceptionHandlers', $exceptionHandlers);
    }
}

