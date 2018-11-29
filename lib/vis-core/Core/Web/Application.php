<?php

namespace Core\Web;

use Core\Common\Obj;
use Core\Common\Str;
use Core\Configuration\ConfigurationManager;
use Core\Web\Annotations\Constraint;
use Core\Web\Http\Server;
use Core\Web\Http\Request;
use Core\Web\Http\Response;
use Core\Web\Http\HttpContext;
use Core\Web\Http\GenericService;
use Core\Web\Http\HttpException;
use Core\Web\Http\ServiceNotFoundException;
use Core\Web\Http\HttpConstraintException;

final class Application{
    
    private $config = null;
    private $httpContext = null;

    public function run(string $baseDir, ConfigurationManager $configReader){
        
        $this->config = $configReader->getConfiguration();
        $server = new Server($baseDir);
        $request = new Request($server);
        $response = new Response($server);
        $this->httpContext = new HttpContext($request, $response);
        
        foreach($this->config->getRoutes() as $route){
            if($route->execute($request)){
                $class = (string)Str::set($route->getServiceClass())->replaceTokens(
                    $request->getParameters()
                    ->map(function($v){ return (string)Str::set($v)->toUpperFirst(); })
                    ->toArray()
                )->replace('.', '\\');
                    
                if(Obj::exists($class)){
                    $service = new $class($this->config);
                }else{
                    throw new ServiceNotFoundException($response, 404, "the service controller not found");
                }

                if($service instanceof GenericService){
                    $this->dispatch($service);
                    break;
                }else{
                    throw new HttpException($response, 500, "$class must be an instance of GenericService");
                }
            }
        }
    }
    
    public function error(\Exception $e){
        $exceptionType = get_class($e);

        foreach($this->config->getExceptionHandlers() as $handler){
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
        $this->httpContext->getResponse()->flush();
    }
}

