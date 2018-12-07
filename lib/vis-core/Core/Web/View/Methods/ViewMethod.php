<?php

namespace Core\Web\View\Methods;

abstract class ViewMethod{
    
    private $methods = null;

    public function addMethods(\Core\Common\Arr $methods){
        $this->methods = $methods;
        return $this;
    }
    
    public function __call($name, $arguments) {
        if($this->methods->exists($name)){
            $class = $this->methods->get($name);
            $class->addMethods($this->methods->toArray());
            return $class->execute(...$arguments);
        }
    }
}