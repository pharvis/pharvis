<?php

namespace Core\Web;

final class Configuration{
    
    private $routes = [];
    private $errorHandlers = [];
    
    public function __construct(\SimpleXMLElement $xml){
        foreach($xml->xpath('//urlPattern') as $urlPattern){

            $attrs = $urlPattern->attributes();
            $handler = isset($attrs['handler']) ? (string)$attrs['handler'] : 'Core.Web.Routing.RouteHandler';
            
            $this->routes[] = new \Core\Web\Routing\Route(
                (string)$urlPattern, 
                $handler,
                (string)$urlPattern->xpath('../..')[0]->class
            );
        }
        
        foreach($xml->errorHandlers->handler as $errorHandler){ 
            $this->errorHandlers[] = (object)['exception' => (string)$errorHandler->exception, 'class' => (string)$errorHandler->class];
        }
    }
    
    public function getRoutes() : array{
        return $this->routes;
    }
    
    public function getErrorHandlers() : array{
        return $this->errorHandlers;
    }
}