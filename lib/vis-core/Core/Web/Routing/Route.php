<?php

namespace Core\Web\Routing;

use Core\Common\Obj;
use Core\Web\Http\Request;

class Route{

    protected $urlPattern;
    protected $routeHandler;
    protected $serviceClass;
    
    public function __construct(string $urlPattern, string $routeHandler, string $serviceClass){
        $this->urlPattern = $urlPattern;
        $this->routeHandler =  Obj::create($routeHandler)->get();
        $this->serviceClass = $serviceClass;
    }
    
    public function getServiceClass() : string{
        return $this->serviceClass;
    }

    public function execute(Request $request){
        return $this->routeHandler->execute($request, $this->urlPattern);
    }
}