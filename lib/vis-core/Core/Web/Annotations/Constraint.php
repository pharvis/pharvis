<?php

namespace Core\Web\Annotations;

abstract class Constraint{
    
    private $errMessage = '';

    public function setErrMessage(string $string){
        $this->errMessage = $string;
    }
    
    public function getErrMessage() : string{
        return $this->errMessage;
    }
    
    public abstract function execute(\Core\Web\Http\HttpContext $httpContext) : bool;
}