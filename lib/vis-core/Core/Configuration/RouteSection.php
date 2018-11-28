<?php

namespace Core\Configuration;

use Core\Web\Routing\Route;

class RouteSection extends ConfigurationSection{
    
    public function execute(\SimpleXMLElement $xml){
        $routes = [];
        
        foreach($xml->xpath('//urlPattern') as $urlPattern){

            $attrs = $urlPattern->attributes();
            $handler = isset($attrs['handler']) ? (string)$attrs['handler'] : 'Core.Web.Routing.RouteHandler';
            
            $routes[] = new Route(
                (string)$urlPattern, 
                $handler,
                (string)$urlPattern->xpath('../..')[0]->class
            );
        }
        
        $this->addSection('routes', $routes);
    }
}

