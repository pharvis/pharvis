<?php

namespace Core\Web\Http;

final class HttpContext{
    
    private $request = null;
    private $response = null;

    public function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
    }
    
    public function getRequest() : Request{
        return $this->request;
    }
    
    public function getResponse() : Response{
        return $this->response;
    }
}