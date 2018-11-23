<?php

namespace Core\Web\Routing;

use Core\Web\Http\Request;

class RouteHandler{
    
    public function execute(Request $request, string $urlPattern) : bool{

        $pattern = str_replace('{', '(?P<', str_replace('}', '>.+)', $urlPattern));
        $matches = [];

        if(preg_match('#^'.$pattern.'$#', $request->getUrl()->getUri(), $matches)){
            foreach($matches as $key => $value){
                if(!is_int($key)){
                    $request->addParameter($key, $value);
                }
            }
            return true;
        }
        return false;
    }
}