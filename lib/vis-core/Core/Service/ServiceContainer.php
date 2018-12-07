<?php

namespace Core\Service;

class ServiceContainer{
    
    protected $container = null;
    
    public function __construct(){
        $this->container = new \Core\Common\Arr();
    }
    
    public function add(string $name, $service){
        $this->container->add($name, $service);
    }
    
    public function get(string $name){
        $service = $this->container->get($name);
        $arguments = [];
        foreach($service->getConstructorArgs() as $argument){
            if($argument->getIsReference()){
                $arguments[] = $this->get($argument->getValue());
            }else{
                $arguments[] = $argument->getValue();
            }
        }
        return \Core\Common\Obj::create($service->getClass(), $arguments)->get();
    }
}