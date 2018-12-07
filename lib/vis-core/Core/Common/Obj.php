<?php

namespace Core\Common;

final class Obj{
    
    private $object = null;
    
    public function __construct($object){
        $this->object = $object;
    }
    
    public function hasMethod(string $methodName) : bool{
        $reflect = new \ReflectionObject($this->object);
        return $reflect->hasMethod($methodName);
    }
    
    public function hasProperty(string $propertyName) : bool{
        $reflect = new \ReflectionObject($this->object);
        return $reflect->hasProperty($propertyName);
    }
    
    public function invokeMethod(string $method, Arr $args){ //print_R($args);
        $reflect = new \ReflectionMethod($this->object, $method);
        $actionArgs = new Arr();
        
        foreach($reflect->getParameters() as $param){
            $defaultValue = $param->isOptional() ? $param->getDefaultValue() : null;

            switch($param->getType()){
                case '':
                case 'string':
                case 'int':
                case 'float':
                case 'bool':
                    if($args->exists($param->name) && $args->get($param->name) != null){
                        $actionArgs->addIndex($args->get($param->name));
                    }else{
                        $actionArgs->addIndex($defaultValue);
                    }
                    break;
                default:
                    if($args->exists($param->name)){
                        $reflectType = new \ReflectionClass($param->getType());

                        if($reflectType->getConstructor()){
                            $actionArgs->addIndex($this->create($param->getType(), [$args->get($param->name)])->get());
                        }else{
                            $actionArgs->addIndex($this->create($param->getType())->setProperties($args->get($param->name))->get());
                        }
                    }else{
                        $actionArgs->addIndex($this->create($param->getType())->get());
                    }
                    break;
            }
        }
        return $reflect->invokeArgs($this->object, $actionArgs->toArray());
    }

    public function setProperties(array $properties = [], array $modifiers = [\ReflectionProperty::IS_PUBLIC, \ReflectionProperty::IS_PROTECTED]){
        $reflect = new \ReflectionObject($this->object);
        foreach($properties as $key=>$value){
            if($reflect->hasProperty($key)){ 
                $property = $reflect->getProperty($key);
                if(in_array($property->getModifiers(), $modifiers)){
                    $property->setAccessible(true);
                    $property->setValue($this->object, $value);
                }
            }
        }
        return $this;
    }
   
    public function get(){
        return $this->object;
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
    
    public static function exists(string $className){
        return class_exists(str_replace('.', '\\', $className));
    }
}