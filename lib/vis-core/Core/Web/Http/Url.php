<?php

namespace Core\Web\Http;

class Url {
    
    private $httpScheme = '';
    private $host = '';
    private $rawUri = '';
    private $uri = '';
    private $url = '';
    private $baseUrl = '';

    public function __construct(Server $server){
        $this->httpScheme = $server->get('REQUEST_SCHEME');
        $this->host = $server->get('HTTP_HOST');
        $this->rawUri = $server->get('REQUEST_URI');
        
        if($this->rawUri && strpos($this->rawUri, '?') > -1){
            $this->uri = substr($this->rawUri, 0, strpos($this->rawUri, '?'));
        }else{
            $this->uri = $this->rawUri;
        }

        $port = $server->get('SERVER_PORT') != 80 ? ':' . $server->get('SERVER_PORT') : '';
        $this->baseUrl = $this->httpScheme . '://' . $this->host . $port;
        $this->url = $this->httpScheme . '://' . $this->host . $port . '/' . $this->rawUri;
    }
    
    public function getHttpScheme() : string{
        return $this->httpScheme;
    }
    
    public function getHost() : string{
        return $this->host;
    }
    
    public function getRawUri() : string{
        return $this->rawUri;
    }
    
    public function getUri() : string{
        return $this->uri;
    }
    
    public function getBaseUrl() : string{
        return $this->baseUrl;
    }
    
    public function __toString(){
        return $this->url;
    }
}