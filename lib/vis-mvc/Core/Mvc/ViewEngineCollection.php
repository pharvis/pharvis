<?php

namespace Core\Mvc;

class ViewEngineCollection implements \IteratorAggregate{
    
    protected $collection = [];
    
    public function add($viewEngine){
        $this->collection[get_class($viewEngine)] = $viewEngine;
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}