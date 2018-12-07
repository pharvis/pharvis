<?php

namespace Core\Web\View;

interface IView{
    
    public function render(array $params = []);
}