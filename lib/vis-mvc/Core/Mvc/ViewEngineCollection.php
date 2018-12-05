<?php

namespace Core\Mvc;

class ViewEngineCollection implements \IteratorAggregate{
    
    protected $collection = [];
    
    public function add($viewEngine){
        $this->collection[get_class($viewEngine)] = $viewEngine;
    }
    
    public function remove(string $class){
        unset($this->collection[$class]);
    }
    
    public function count() : int{
        return count($this->collection);
    }
    
    public function getTypeOf(string $class) : ViewEngine{
        if(array_key_exists($class, $this->collection)){
            return $this->collection[$class];
        }
    }

    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}