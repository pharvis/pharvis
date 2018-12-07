<?php

namespace Core\Web\Http;

abstract class HttpController extends GenericController{
    
    public function get(HttpContext $httpContext){}

    public function post(HttpContext $httpContext){}
    
    public function put(HttpContext $httpContext){}
    
    public function delete(HttpContext $httpContext){}
    
    public function options(HttpContext $httpContext){}
    
    public function head(HttpContext $httpContext){}
    
    public function service(HttpContext $httpContext){

        switch($httpContext->getRequest()->getMethod()){
            case 'GET':
                $this->get($httpContext);
                break;
            
            case 'POST':
                $this->post($httpContext);
                break;
            
            case 'PUT':
                $this->put($httpContext);
                break;
            
            case 'DELETE':
                $this->delete($httpContext);
                break;
            
            case 'OPTIONS':
                $this->options($httpContext);
                break;
            
            case 'HEAD':
                $this->head($httpContext);
                break;
        }
    }
}