<?php

namespace Core\Web\Http;

use Core\Web\Http\Response;

class HttpException extends \Exception{
    
    public function __construct(Response $response, int $statusCode, string $message, \Exception $prev = null){
        parent::__construct($message, $this->code, $prev);
        $response->setStatusCode($statusCode);
    }
}