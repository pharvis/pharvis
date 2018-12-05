<?php

namespace Core\Mvc;

use Core\Common\Str;
use Core\Web\View\NativeView;

class NativeViewEngine{
    
    private $locationFormats = [];
    private $isDefault = false;

    public function __construct(){
        $this->locationFormats = ['~/views/{controller}/{action}.php'];
    }
    
    public function setViewLocationFormats(array $locationFormats){
        $this->locationFormats = array_merge($this->locationFormats, $locationFormats);
        return $this;
    }

    public function setIsDefault(bool $isDefault){
        $this->isDefault = $isDefault;
        return $this;
    }
    
    public function getIsDefault() : bool{
        return $this->isDefault;
    }

    public function findView($httpContext){
        
        foreach($this->locationFormats as $location){
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