<?php

namespace Core\Web\Http;

class ExceptionDispatcher extends HttpDispatcher{
    
    public function get(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }

    public function post(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }
    
    public function put(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }
    
    public function delete(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }
    
    public function options(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }
    
    public function head(HttpContext $httpContext){
        $this->handleRequest($httpContext);
    }
    
    protected function handleRequest(HttpContext $httpContext){
        print_R($httpContext->getRequest()->getException());
    }
}
