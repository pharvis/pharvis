<?php

namespace Core\Web\Http;

interface IGenericDispatcher{
    
    public function service(HttpContext $httpContext);
}