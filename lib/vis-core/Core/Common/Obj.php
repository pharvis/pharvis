<?php

namespace Core\Common;

final class Obj{
    
    private $object = null;
    
    public function __construct($object){
        $this->object = $object;
    }
    
    public function getClassAnnotations(){
        $reflect = new \ReflectionObject($this->object);
        $segments = Str::set($reflect->getDocComment())->split("\n");
        
        foreach($segments->toStringGenerator() as $segment){
            if($segment->trim('/* ')->startsWith('@')){
                yield new Annotation((string)$segment->leftTrim('@')); 
            }
        }
    }
    
    public function get(){
        return $this->object;
    }

    public static function create(string $className, array $args = []){
        $reflect = new \ReflectionClass(str_replace('.', '\\', $className));

        if(count($args) > 0){
            return  new Obj($reflect->newInstanceArgs($args));
        }else{
            return new Obj($reflect->newInstance());
        }
    }
    
    public static function from($object){
        return new Obj($object);
    }
}