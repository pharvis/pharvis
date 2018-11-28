<?php

namespace Core\Configuration;

final class Configuration{
    
    private $collection = [];
    
    public function getRoutes(){
        if($this->exists('routes')){
            return $this->get('routes');
        }
    }

    public function getSettings(){
        if($this->exists('settings')){
            return $this->get('settings');
        }
    }
    
    public function getServiceContainer(){
        if($this->exists('serviceContainer')){
            return $this->get('serviceContainer');
        }
    }

    public function add($name, $value){
        if(false === array_key_exists($name, $this->collection)){
            $this->collection[$name] = $value;
        }else{
            throw new ConfigurationException(sprintf("Configuration section '%s' already exists. Section cannot be overriden.", $name));
        }
    }
    
    public function get($name){
        return $this->collection[$name];
    }
    
    public function exists(string $name){
        if(array_key_exists($name, $this->collection)){
            return $this->collection[$name];
        }
        throw new ConfigurationException(sprintf("Configuration section '%s' does not exist.", $name));
    }
}