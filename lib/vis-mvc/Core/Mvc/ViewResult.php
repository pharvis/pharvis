<?php

namespace Core\Mvc;

use Core\Common\Str;
use Core\Web\Http\HttpContext;

class ViewResult implements IActionResult{
    
    protected $viewPathPattern = '/Views/{controller}/{action}.php';
    protected $httpContext = null;
    protected $params = [];
    
    public function __construct(HttpContext $httpContext, array $params = []){
        $this->path = $httpContext->getRequest()->getServer()->getBasePath();
        $this->httpContext = $httpContext;
    }
    
    public function execute() : string{
        $parameters = $this->httpContext->getRequest()->getParameters();
        
        $path = (string)Str::set($this->viewPathPattern)->replaceTokens(
            $parameters
            ->map(function($v){ return (string)Str::set($v)->toUpperFirst(); })
            ->toArray()
        );
            return $this->path . $path;
        //print $path; exit;
    }
}