<?php

namespace Core\Web\Http;

use Core\Common\Arr;
use Core\Common\Str;

final class Response{
    
    private $server = null;
    private $output = null;
    private $headers = [];

    public function __construct(Server $server){
        $this->server = $server;
        $this->headers = new Arr();
        $this->output = new Str();
    }
    
    public function addHeader(string $header, string $value){
        $this->headers->add($header, $value);
    }
    
    public function setContentType(string $contentType, string $encoding = null){
        $this->addHeader('Content-type', $contentType . '; charset=' . ((null === $encoding) ? $this->output->getEncoding() : $encoding));
        return $this;
    }

    public function write(string $string){
        $this->output->append($string);
        return $this;
    }
    
    public function flush(){
        if(!headers_sent()){   
            foreach($this->headers as $header => $value){
                header($header . ':'. $value, true);
            }
        }
        echo $this->output;
    }
}