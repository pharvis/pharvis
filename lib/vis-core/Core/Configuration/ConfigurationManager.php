<?php

namespace Core\Configuration;

class ConfigurationManager{
    
    private $configuration = null;
    
    public function __construct(\SimpleXMLElement $xml){
        $this->xml = $xml;
        $this->configuration = new Configuration();
        
        $sections = [
            new RouteSection(),
            new SettingsSection(),
            new ServiceContainerSection(),
            new ExceptionHandlerSection(),
        ];
        
        foreach($sections as $section){
            $this->handleSection($section);
        }
    }
    
    public function getConfiguration(){
        return $this->configuration;
    }
    
    public function handleSection(IConfigurationSection $section){
        $section->execute($this->configuration, $this->xml);
    }
}
