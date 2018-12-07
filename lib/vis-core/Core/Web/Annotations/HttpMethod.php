<?php

namespace Core\Web\Annotations;

class HttpMethod extends Constraint{
    
    protected $methods = '';
    
    public function __construct(string ...$methods){
        $this->methods = $methods;
    }
    
    public function execute(\Core\Web\Http\HttpContext $httpContext) : bool{
        $this->setErrMessage(sprintf("HTTP request method '%s' not allowed.", $httpContext->getRequest()->getServer()->get('REQUEST_METHOD')));
        return in_array($httpContext->getRequest()->getMethod(), $this->methods);
    }
}