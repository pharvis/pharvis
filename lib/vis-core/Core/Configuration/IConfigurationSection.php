<?php

namespace Core\Configuration;

interface IConfigurationSection{
   
    public function execute(Configuration $configuration, \SimpleXMLElement $xml);
}