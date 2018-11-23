<?php

namespace Core\Web;

use Core\Common\Obj;
use Core\Web\Http\Server;
use Core\Web\Http\Request;
use Core\Web\Http\Response;
use Core\Web\Http\HttpContext;
use Core\Web\Http\IGenericDispacher;

final class Application{
    
    private $baseDir = '';
    private $config = null;
    private $httpContext = null;
    private $server = null;
    private $request = null;
    private $response = null;

    public function __construct(string $baseDir, Configuration $config){
        $this->baseDir = $baseDir;
        $this->config = $config;
        $this->server = new Server();
        $this->request = new Request($this->server);
        $this->response = new Response($this->server);
        $this->httpContext = new HttpContext($this->request, $this->response);
    }
    
    public function run(){ 
        foreach($this->config->getRoutes() as $route){
            if($route->execute($this->request)){
                $class = $route->getDispatcherClass();
                
                $instance = Obj::create($class)->get();
                
                if($instance instanceof IGenericDispacher){
                    $instance->service($this->httpContext);
                    break;
                }else{
                    throw new \RuntimeException("$class must be an instance of IGenericDispacher");
                }
            }
        }
    }
    
    public function error(\Exception $e){ 
        $exceptionType = get_class($e);
        
        foreach($this->config->getErrorHandlers() as $handler){
            if($handler->exception == $exceptionType || $handler->exception =='*'){
                
                $instance = Obj::create($handler->class)->get();
                
                if($instance instanceof IGenericDispacher){
                    $this->httpContext->getRequest()->setException($e);
                    $instance->service($this->httpContext);
                    break;
                }
            }
        }
    }
}

