<?php

namespace Core\Mvc;

use Core\Common\Obj;
use Core\Web\Http\GenericService;
use Core\Web\Http\HttpContext;
use Core\Web\Http\Request;
use Core\Web\Http\Response;

abstract class Controller extends GenericService{
    
    private $httpContext = null;
    private $request = null;
    private $response = null;
    
    public function service(HttpContext $httpContext){
        
        $this->httpContext = $httpContext;
        $this->request = $httpContext->getRequest();
        $this->response = $httpContext->getResponse();
        
        $collection = $this->request->getCollection();
        $parameters = $this->request->getParameters();
        
        $action = $parameters->exists('action') ? $parameters->get('action') : $parameters->add('action', 'index')->get('action');

        if(Obj::from($this)->hasMethod($action)){
            $this->load();
            $result = Obj::from($this)->invokeMethod($action, $collection);

            if(!$result instanceof IActionResult){
                if(is_scalar($result)){
                    $actionResult = new StringResult($result);
                }else{
                    $actionResult = new JsonResult($this->response, $result);
                }
            }else{
                $actionResult = $result;
            }
            $this->render($actionResult->execute());
        }
    }
    
    public function getRequest() : Request{
        return $this->request;
    }
    
    public function getResponse() : Response{
        return $this->response;
    }

    public function view(array $params = []){
        $viewResult = new ViewResult($this->httpContext, $params);
        return $viewResult;
    }
    
    public function load(){}
    
    public function render(string $response){
        $this->response->write($response);
    }
}