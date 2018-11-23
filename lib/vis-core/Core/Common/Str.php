<?php

namespace Core\Common;

final class Str{
    
    private $string = '';
    private $encoding = '';

    public function __construct(string $string = '', $encoding = null){
        $this->string = $string;
        $this->encoding = (null === $encoding) ? mb_internal_encoding() : $encoding;
    }
    
    public function length(){
        return mb_strlen($this->string, $this->encoding);
    }
    
    public function append(string $string){
        $this->string .= $string;
        return $this;
    }
    
    public function subString(int $start, int $length = null){
        $this->string = mb_substr($this->string, $start, $length, $this->encoding);
        return $this;
    }
    
    public function indexOf(string $needle , $offset = 0){        
        return mb_stripos($this->string, $needle, $offset, $this->encoding);
    }

    public function toUpper(){
        $this->string = mb_strtoupper($this->string, $this->encoding);
        return $this;
    }
    
    public function toLower(){
        $this->string = mb_strtolower($this->string, $this->encoding);
        return $this;
    }
    
    public function toUpperFirst(){ 
        $this->string = mb_strtoupper(mb_substr($this->string, 0, 1, $this->encoding), $this->encoding) . mb_substr($this->string, 1, null, $this->encoding);
        return $this;
    }
    
    public function toUpperLast(){
        $this->string = mb_substr($this->string, 0, -1, $this->encoding) . mb_strtoupper(mb_substr($this->string, -1, 1, $this->encoding), $this->encoding);
        return $this;
    }
    
    public function trim(string $charmask = null){
        $this->string = $this->_trim('trim', $charmask);
        return $this;
    }
    
    public function leftTrim(string $charmask = null){
        $this->string = $this->_trim('ltrim', $charmask);
        return $this;
    }
    
    public function rightTrim(string $charmask = null){
        $this->string = $this->_trim('rtrim', $charmask);
        return $this;
    }
    
    public function split(string $pattern, int $limit = null, int $flags = PREG_SPLIT_NO_EMPTY){
        return new Arr(preg_split('@'.$pattern.'@', $this->string, $limit, $flags));
    }
    
    public function getEncoding(){
        return $this->encoding;
    }

    public function toString(){
        return $this->__toString();
    }
    
    public function __toString(){
        return $this->string;
    }
    
    private function _trim($function, $charmask = null){
        return $function($this->string, ((null == $charmask) ? " \t\n\r\0\x0B" : " \t\n\r\0\x0B" . $charmask));
    }
}
