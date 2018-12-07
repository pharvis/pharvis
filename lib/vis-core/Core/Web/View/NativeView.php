<?php

namespace Core\Web\View;

use Core\Common\Str;

class NativeView implements IView{
    
    protected $basePath = '';
    protected $viewFiles = [];
    protected $childOutput = '';
    protected $layoutView = null;
    protected $methods = null;
    
    public function __construct(NativeView $layoutView = null){
        $this->layoutView = $layoutView;
        $this->methods = new \Core\Common\Arr();
    }
    
    public function setLayout($viewFile){
        $this->layoutView = new NativeView();
        $this->layoutView->setBasePath($this->basePath);
        $this->layoutView->setViewFiles($viewFile);
    }
    
    public function setBasePath(string $basePath){
        $this->basePath = $basePath;
    }
    
    public function getBasePath() : string{
        return $this->basePath;
    }

    public function setViewFiles($viewFile){
        if(is_string($viewFile)){
            $this->viewFiles[] = $viewFile;
        }elseif(is_array($viewFile)){
            $this->viewFiles = array_merge($this->viewFiles, $viewFile);
        }
    }
    
    public function getViewFiles() : array{
        return $this->viewFiles;
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

        foreach($this->viewFiles as $viewFile){
            if(Str::set($viewFile)->subString(0,1) == '~'){
                $viewFile = $this->basePath . (string)Str::set($viewFile)->subString(1);
            }
            
            if(!is_file($viewFile)){
                continue;
            }

            ob_start();
            include $viewFile;
            $output = ob_get_clean();
            

            if($this->layoutView !== null){ 
                $this->layoutView->setBasePath($this->basePath);
                $this->layoutView->addMethods($this->methods->toArray());
                $this->layoutView->setChildOutput($output); 
                $output =  $this->layoutView->render($params);
            }
            return $output;
        }
        
        throw new ViewFileNotFoundException("The view was not found: searched tried " . print_R($this->viewFiles, true));
    }
    
    public function __call($name, $arguments) {
        if($this->methods->exists($name)){
            $class = $this->methods->get($name);
            $class->addMethods($this->methods);
            return $class->execute(...$arguments);
        }
        throw new \Exception("Method not found $name");
    }
    
    private function setChildOutput(string $childOutput){
        $this->childOutput = $childOutput;
    }
}