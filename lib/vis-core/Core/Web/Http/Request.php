<?php

namespace Core\Web\Http;

use Core\Common\Arr;

final class Request{
    
    private $server = null;
    private $url = null;
    private $exception = null;
    private $parameter = null;

    public function __construct(Server $server){
        $this->server = $server;
        $this->url = new Url($server);
        $this->parameter = new Arr();
    }
    
    public function getUrl() : Url{
        return $this->url;
    }
    
    public function getMethod(){
        return $this->server->get('REQUEST_METHOD', 'GET');
    }
    
    public function setException(\Exception $exception){
        $this->exception = $exception;
    }
    
    public function getException() : \Exception{
        return $this->exception;
    }
    
    public function addParameter(string $name, string $value){
        $this->parameter->add($name, $value);
    }
}