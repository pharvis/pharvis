<?php

namespace Core\Common;

class Annotation{
    
    protected $className = '';
    protected $parameters = [];
    
    public function __construct(string $stringAnnotation){
        $tokens = new Arr(token_get_all('<?php ' . $stringAnnotation . ' ?>')); 
       //print_R($tokens);
        $captureClassName = true;
 //print token_name(318);exit;
        for($i=0; $i < $tokens->count(); $i++){
           
            $token = $tokens->get($i);
            
            if(is_array($token)){
                switch($token[0]){
                    case T_STRING:
                    case T_CONSTANT_ENCAPSED_STRING:
                    case T_LNUMBER:
                    case T_DNUMBER:
                        if($captureClassName){
                            $this->className .= $token[1] . '.';
                        }else{
                            if($token[0] == T_CONSTANT_ENCAPSED_STRING){
                                $this->parameters[] = trim($token[1], '"');
                            }else{
                                $this->parameters[] = $token[1];
                            }
                        }
                        break;
                }
            }else{
                if($token == '('){
                    $captureClassName = false;
                }
            }
        }
        $this->className = rtrim($this->className, '.');
    }
    
    public function getClassName() : string{
        return $this->className;
    }
    
    public function getParameters() : array{
        return $this->parameters;
    }
}