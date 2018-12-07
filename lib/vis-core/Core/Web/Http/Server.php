<?php

namespace Core\Web\Http;

use Core\Common\Arr;

final class Server{
    
    private $basePath = '';
    private $collection = null;
    
    public function __construct(string $basePath){
        $this->basePath = $basePath;
        $this->collection = new Arr($_SERVER);
    }
    
    public function get(string $key, $default = null){
        if($this->exists($key)){
            return $this->collection[$key];
        }
        
        if(null != $default){
            return $default;
        }
    }
    
    public function exists(string $key) : bool{
        return $this->collection->exists($key);
    }
    
    public function getBasePath(){
        return $this->basePath;
    }
}