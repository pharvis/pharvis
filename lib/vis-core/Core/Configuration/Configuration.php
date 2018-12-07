<?php

namespace Core\Configuration;

final class Configuration{
    
    private $collection = [];
    
    public function add($name, $value){
        if(false === array_key_exists($name, $this->collection)){
            $this->collection[$name] = $value;
        }else{
            throw new ConfigurationException(sprintf("Configuration section '%s' already exists. Section cannot be overriden.", $name));
        }
    }
    
    public function get($name){
        if($this->exists($name)){
            return $this->collection[$name];
        }
        return false;
    }
    
    public function exists(string $name) : bool{
        return array_key_exists($name, $this->collection);
    }
}