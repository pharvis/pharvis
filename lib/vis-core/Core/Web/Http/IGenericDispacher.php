<?php

namespace Core\Web\Http;

interface IGenericDispacher{
    
    public function service(HttpContext $httpContext);
}