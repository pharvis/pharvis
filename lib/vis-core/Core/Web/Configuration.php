<?php

namespace Core\Web;

use Core\Web\Routing\Route;
use Core\Service\ServiceContainer;
use Core\Service\Service;
use Core\Service\Argument;

final class Configuration{
    
    private $routes = [];
    private $serviceContainer = null;
    private $settings = [];
    private $errorHandlers = [];
    
    public function __construct(\SimpleXMLElement $xml){
        foreach($xml->xpath('//urlPattern') as $urlPattern){

            $attrs = $urlPattern->attributes();
            $handler = isset($attrs['handler']) ? (string)$attrs['handler'] : 'Core.Web.Routing.RouteHandler';
            
            $this->routes[] = new Route(
                (string)$urlPattern, 
                $handler,
                (string)$urlPattern->xpath('../..')[0]->class
            );
        }

        $this->loadSettings($xml->settings);

        $this->serviceContainer = new ServiceContainer();
        
        foreach($xml->services->service as $serv){
            $service = new Service($serv->class);
            
            foreach($serv->constructorArg as $arg){
                $argument = new Argument();
               
                if(isset($arg['type']) && $arg['type'] == 'property'){
                    $arg = $this->getSettings((string)$arg);
                }
                if(isset($arg['type']) && $arg['type'] == 'ref'){
                    $argument->setIsReference(true);
                }
                
                $argument->setValue((string)$arg);

                $service->addConstructorArg($argument);
            }
            
            $this->serviceContainer->add((string)$serv['name'], $service);
        }

        foreach($xml->errorHandlers->handler as $errorHandler){ 
            $this->errorHandlers[] = (object)['exception' => (string)$errorHandler->exception, 'class' => (string)$errorHandler->class];
        }
    }
    
    public function getRoutes() : array{
        return $this->routes;
    }
    
    public function getServiceContainer() : ServiceContainer{
        return $this->serviceContainer;
    }
    
    public function getSettings(string $path = ''){
        if($path == ''){
            return $this->settings;
        }

        $segments = array_map(function($value){ return trim($value); }, explode('.', trim($path, ' .')));
        $tmp = $this->settings;
        foreach($segments as $segment){
            $tmp = $tmp[$segment];
        }
        return $tmp;
    }

    public function getErrorHandlers() : array{
        return $this->errorHandlers;
    }
    
    private function loadSettings($settings){
        foreach($settings->section as $section){ 
            foreach($section->property as $property){
                $this->settings[(string)$section['name']][(string)$property['name']] = (string)$property['value'];
            }
        }
        
        if($settings['include']){
            if(is_file($settings['include'])){
                $this->loadSettings(simplexml_load_file($settings['include']));
            }else{
                throw new \Exception('not found');
            }
        }
    }
}