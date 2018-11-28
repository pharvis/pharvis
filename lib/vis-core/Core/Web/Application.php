<?php

namespace Core\Web;

use Core\Common\Obj;
use Core\Web\Annotations\Constraint;
use Core\Web\Http\Server;
use Core\Web\Http\Request;
use Core\Web\Http\Response;
use Core\Web\Http\HttpContext;
use Core\Web\Http\GenericService;
use Core\Web\Http\HttpException;
use Core\Web\Http\HttpConstraintException;

final class Application{
    
    private $baseDir = '';
    private $config = null;
    private $httpContext = null;
    private $server = null;
    private $request = null;
    private $response = null;

    public function run(string $baseDir, \Core\Configuration\ConfigurationReader $configReader){
        
        $this->baseDir = $baseDir;
        $this->config = $configReader->getConfiguration();
        $this->server = new Server();
        $this->request = new Request($this->server);
        $this->response = new Response($this->server);
        $this->httpContext = new HttpContext($this->request, $this->response);
        
        foreach($this->config->getRoutes() as $route){
            if($route->execute($this->request)){
                $class = $route->getServiceClass();
                
                $service = Obj::create($class, [$this->config])->get();

                if($service instanceof GenericService){
                    $this->dispatch($service);
                    break;
                }else{
                    throw new HttpException("$class must be an instance of GenericService");
                }
            }
        }
    }
    
    public function error(\Exception $e){ print_R($e); exit;
        $exceptionType = get_class($e);
        
        foreach($this->config->getErrorHandlers() as $handler){
            if($handler->exception == $exceptionType || $handler->exception =='*'){
                
                $service = Obj::create($handler->class, [$this->config])->get();

                if($service instanceof GenericService){
                    $this->httpContext->getRequest()->setException($e);
                    $this->dispatch($service);
                    break;
                }
            }
        }
    }
    
    protected function dispatch(GenericService $service){
        $annotations = Obj::from($service)->getClassAnnotations();

        foreach($annotations as $annotation) {
            $annotationInstance = Obj::create($annotation->getClassName(), $annotation->getParameters())->get();

            if($annotationInstance instanceof Constraint){
                if(false === $annotationInstance->execute($this->httpContext)){
                    throw new HttpConstraintException($annotationInstance->getErrMessage());
                }
            }
        }
        $service->service($this->httpContext);
    }
}

