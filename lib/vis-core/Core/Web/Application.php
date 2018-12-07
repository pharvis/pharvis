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
use Core\Web\Http\GenericController;
use Core\Web\Http\HttpException;
use Core\Web\Http\ControllerNotFoundException;
use Core\Web\Http\HttpConstraintException;

final class Application{
    
    private $configManager = null;
    private $httpContext = null;

    public function run(string $baseDir, ConfigurationManager $configManager){
        
        $this->configManager = $configManager;
        $server = new Server($baseDir);
        $request = new Request($server);
        $response = new Response($server);
        $this->httpContext = new HttpContext($request, $response);
        
        foreach($this->configManager->getConfiguration()->get('routes') as $route){ 
            if($route->execute($request)){
                $class = (string)Str::set($route->getServiceClass())->replaceTokens(
                    $request->getParameters()
                    ->map(function($v){ return (string)Str::set($v)->toUpperFirst(); })
                    ->toArray()
                )->replace('.', '\\');

                if(Obj::exists($class)){
                    $controller = new $class($this->configManager);
                }else{
                    throw new ControllerNotFoundException($response, 404, "the service controller not found");
                }

                if($controller instanceof GenericController){
                    $this->dispatch($controller);
                    break;
                }else{
                    throw new HttpException($response, 500, "$class must be an instance of GenericService");
                }
            }
        }
    }
    
    public function error(\Exception $e){  print_R($e); exit;
        $exceptionType = get_class($e);

        foreach($this->configManager->getConfiguration()->get('exceptionHandlers') as $handler){
            if($handler->exception == $exceptionType || $handler->exception =='*'){
                
                $class = (string)Str::set($handler->class)->replace('.', '\\');

                $controller = new $class($this->configManager);
                
                if($controller instanceof GenericController){
                    $this->httpContext->getRequest()->setException($e);
                    $this->dispatch($controller);
                    break;
                }
            }
        }
    }
    
    protected function dispatch(GenericController $controller){
        $annotations = Obj::from($controller)->getClassAnnotations();

        foreach($annotations as $annotation) {
            $annotationInstance = Obj::create($annotation->getClassName(), $annotation->getParameters())->get();

            if($annotationInstance instanceof Constraint){
                if(false === $annotationInstance->execute($this->httpContext)){
                    throw new HttpConstraintException($annotationInstance->getErrMessage());
                }
            }
        }
        $controller->service($this->httpContext);
        $this->httpContext->getResponse()->flush();
    }
}

