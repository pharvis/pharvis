<?php

namespace Core\Web\View;

class NativeView implements IView{
    
    protected $parentView = null;
    protected $path = '';
    protected $childOutput = '';
    protected $methods = null;
    
    public function __construct(IView $parentView = null){
        $this->parentView = $parentView;
        $this->methods = new \Core\Common\Arr();
    }

    public function setPath(string $path){
        $this->path = $path;
    }
    
    public function addMethod(string $name, Methods\ViewMethod $method){
        $this->methods->add($name, $method);
        return $this;
    }
    
    public function addMethods($methods){
        $this->methods->merge($methods);
        return $this;
    }

    public function renderBody(){
        echo $this->childOutput;
    }

    public function render(array $params = []){
        extract($params);
        $output = '';
        if(is_file($this->path)){
            ob_start();
            include $this->path;
            $output = ob_get_clean();
        }
        
        if($this->parentView !== null){
            $this->parentView->addMethods($this->methods->toArray());
            $this->parentView->setChildOutput($output);
            $output =  $this->parentView->render($params);
        }
        return $output;
    }
    
    private function setChildOutput(string $childOutput){
        $this->childOutput = $childOutput;
    }
    
    public function __call($name, $arguments) {
        if($this->methods->exists($name)){
            $class = $this->methods->get($name);
            $class->addMethods($this->methods);
            return $class->execute(...$arguments);
        }
    }
}