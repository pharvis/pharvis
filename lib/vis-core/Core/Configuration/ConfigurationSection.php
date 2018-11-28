<?php

namespace Core\Configuration;

abstract class ConfigurationSection{
    
    private $configuration = null;
    
    public function __construct(Configuration $configuration){
        $this->configuration = $configuration;
    }
    
    public function addSection(string $name, $value){
        $this->configuration->add($name, $value);
    }
    
    public function getSection(string $name){
        return $this->configuration->get($name);
    }

    public abstract function execute(\SimpleXMLElement $xml);
}