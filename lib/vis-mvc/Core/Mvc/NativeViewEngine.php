<?php

namespace Core\Mvc;

use Core\Common\Str;
use Core\Web\View\NativeView;

class NativeViewEngine extends ViewEngine{
    
    public function __construct(){
        $this->setViewLocationFormats(['~/views/{controller}/{action}.php']);
    }

    public function findView($httpContext){
        
        foreach($this->getViewLocationFormats() as $location){
            if($location[0] == '~'){
                $location = $httpContext->getRequest()->getServer()->getBasePath() . (string)Str::set($location)->subString(1);
            }

            $file = (string)Str::set($location)->replaceTokens(
                $httpContext->getRequest()->getParameters()
                ->map(function($v){ return (string)Str::set($v)->toUpperFirst(); })
                ->toArray()
            );
                
            if(is_file($file)){
                $view = new NativeView();
                $view->setViewFile($file);
                return $view;
            }
        }
    }
}