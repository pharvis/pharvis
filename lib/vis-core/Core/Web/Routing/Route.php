<?php

namespace Core\Web\Routing;

use Core\Common\Obj;
use Core\Web\Http\Request;

class Route{

    protected $urlPattern;
    protected $routeHandler;
    protected $dispatcherClass;
    
    public function __construct(string $urlPattern, string $routeHandler, string $dispatcherClass){
        $this->urlPattern = $urlPattern;
        $this->routeHandler =  Obj::create($routeHandler)->get();
        $this->dispatcherClass = $dispatcherClass;
    }
    
    public function getDispatcherClass() : string{
        return $this->dispatcherClass;
    }

    public function execute(Request $request){
        return $this->routeHandler->execute($request, $this->urlPattern);
    }
}