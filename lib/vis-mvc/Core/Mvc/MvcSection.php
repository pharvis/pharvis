<?php

namespace Core\Mvc;

use Core\Common\Obj;
use Core\Configuration\IConfigurationSection;
use Core\Configuration\Configuration;

class MvcSection implements IConfigurationSection{
    
    public function execute(Configuration $configuration, \SimpleXMLElement $xml){
        $viewEngines = new ViewEngineCollection();
        $viewEngines->add((new NativeViewEngine())->setIsDefault(true));
        
        if(isset($xml->viewEngines)){
            foreach($xml->viewEngines->engine as $engine){
                $default = strtolower($xml->viewEngines->engine['default']) == 'true' ? true : false;
                $viewEngine = Obj::create((string)$engine->class)->get();
                $viewEngine->setViewLocationFormats((array)$engine->locationFormat);
                $viewEngine->setIsDefault($default);
                $viewEngines->add($viewEngine);
            }
        }

        $configuration->add('viewEngines', $viewEngines);
    }
}