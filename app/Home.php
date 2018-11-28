<?php

namespace app;

use Core\Web\Http\HttpContext;
use Core\Web\Http\HttpService;
use Core\Web\View\NativeView;

/**
 * @Core.Web.Annotations.HttpMethod(POST, GET)
 * @Core.Web.Annotations.HttpMethod(POST, GET)            
 */
class Home extends HttpService{
    
    public function get(HttpContext $httpContext){ //print_R($httpContext->getRequest()); exit;

        //print_R($this->getSettings()->db3->host); exit;
        
        print_R($this->c); exit;
        $view1 = new NativeView();
        $view1->setPath('/var/www/parvus/app/views/shared/main.php');
        
        $view2 = new NativeView($view1);
        $view2->setPath('/var/www/parvus/app/views/home/index.php');
        
        $view3 = new NativeView($view2);
        $view3->setPath('/var/www/parvus/app/views/home/index2.php');
        $view3->addMethod('escape', new \Core\Web\View\Methods\Escape());

        print $view3->render([
            'username' => 'Syed'
        ]);
    }
}