<?php

namespace Core\Web\View\Methods;

class Escape extends ViewMethod{
    
    public function execute(string $string){
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }
}