<?php

namespace Core\Configuration;

class SettingsSection extends ConfigurationSection{
    
    private $settings = [];
    
    public function execute($xml){
        $this->loadSettings($xml->settings);
        $this->addSection('settings', new \Core\Common\Arr($this->settings));
    }
    
    private function loadSettings($settings){
        foreach($settings->section as $section){ 
            foreach($section->property as $property){
                $this->settings[(string)$section['name']][(string)$property['name']] = (string)$property['value'];
            }
        }
        
        if($settings['include']){
            if(is_file($settings['include'])){
                $this->loadSettings(simplexml_load_file($settings['include']));
            }else{
                throw new \Exception('not found');
            }
        }
    }
}

