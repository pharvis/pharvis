<?php

namespace Core\Common;

class Arr implements \ArrayAccess, \IteratorAggregate{
    
    protected $collection = [];
    
    public function __construct(array $collection = []){
        $this->collection = $collection;
    }
    
    public function get(string $key, $default = null){
        if($this->exists($key)){
            return $this->collection[$key];
        }
        if(null !== $default){
            return $default;
        }
    }
    
    public function add(string $key, $value){
        $this->collection[$key] = $value;
    }
    
    public function merge($array){
        if($array instanceof Arr){
            $array = $array->toArray();
        }
        if(!is_array($array)){
            throw new \InvalidArgumentException(sprintf('Argument 1 passed to %1$s::merge() must be of type array or an instance of %1$s, %2$s given.', get_class($this), gettype($array)));
        }
        $this->collection = array_merge($this->collection, $array);
    }
    
    public function exists(string $key) : bool{
        return array_key_exists($key, $this->collection);
    }
    
    public function toArray(){
        return $this->collection;
    }

    public function offsetExists($offset) : bool{
        if (array_key_exists($offset, $this->collection)){
            return true;
        }
        return false;
    }

    public function offsetGet($offset){
        return $this->collection[$offset];
    }
    
    public function offsetSet($offset, $value){
        $this->collection[$offset] = $value;
    }

    public function offsetUnset($offset){
        unset($this->collection[$offset]);
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}