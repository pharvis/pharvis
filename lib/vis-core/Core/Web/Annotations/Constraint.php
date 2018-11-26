<?php

namespace Core\Web\Annotations;

interface Constraint{
    
    public function execute(\Core\Web\Http\HttpContext $httpContext) : bool;
}