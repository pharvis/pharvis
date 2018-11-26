<?php

namespace Core\Web;

use Core\Common\Obj;
use Core\Web\Annotations\Constraint;
use Core\Web\Http\Server;
use Core\Web\Http\Request;
use Core\Web\Http\Response;
use Core\Web\Http\HttpContext;
use Core\Web\Http\IGenericDispatcher;
use Core\Web\Http\HttpException;
use Core\Web\Http\HttpConstraintException;

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
                
                $dispatcher = Obj::create($class)->get();

                if($dispatcher instanceof IGenericDispatcher){

                    $annotations = Obj::from($dispatcher)->getClassAnnotations();

                    foreach($annotations as $annotation) {
                        $annotationInstance = Obj::create($annotation->getClassName(), $annotation->getParameters())->get();

                        if($annotationInstance instanceof Constraint){
                            if(false === $annotationInstance->execute($this->httpContext)){
                                throw new HttpConstraintException(sprintf("Protocol scheme '%s' is not supported.", $this->httpContext->getRequest()->getServer()->get('REQUEST_SCHEME')));
                            }
                        }
                    }
                    
                    $dispatcher->service($this->httpContext);
                    break;
                }else{
                    throw new HttpException("$class must be an instance of IGenericDispatcher");
                }
            }
        }
    }
    
    public function error(\Exception $e){
        $exceptionType = get_class($e);
        
        foreach($this->config->getErrorHandlers() as $handler){
            if($handler->exception == $exceptionType || $handler->exception =='*'){
                
                $instance = Obj::create($handler->class)->get();

                if($instance instanceof IGenericDispatcher){
                    $this->httpContext->getRequest()->setException($e);
                    $instance->service($this->httpContext);
                    break;
                }
            }
        }
    }
}

