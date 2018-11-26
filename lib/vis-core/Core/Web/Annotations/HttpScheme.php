<?php

namespace Core\Web\Annotations;

class HttpScheme implements Constraint{
    
    protected $schemes = '';
    
    public function __construct(string $schemes){
        $this->schemes = $schemes;
    }
    
    public function execute($dispatcher) : bool{
        $array = array_map(function($value){
            return (string)\Core\Common\Str::set($value)->trim()->toLower();
        }, explode(",", $this->schemes));
        

        if(in_array($dispatcher->getRequest()->getServer()->get('REQUEST_SCHEME'), $array)){
            return true;
        }
        return false;
    }
}