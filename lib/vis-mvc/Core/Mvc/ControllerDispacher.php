<?php

namespace Core\Mvc;

use Core\Web\Http\IGenericDispacher;
use Core\Web\Http\HttpContext;

class ControllerDispacher implements IGenericDispacher{
    
    public function service(HttpContext $httpContext){ print_R($httpContext->getRequest()); exit;
        $c = new \Core\Mvc\Controller();
        $c->test(); // print "OK"; exit;
    }
}