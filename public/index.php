<?php

$baseDir = str_replace('\\', '/', dirname(__DIR__));

$libs = ['vis-core', 'vis-mvc', 'vis-data'];

foreach($libs as $lib){
    $p = new Phar($baseDir . '/lib/' . $lib . '.phar');
    $p->buildFromDirectory($baseDir . '/lib/' . $lib);
    $p->setDefaultStub('index.php', 'index.php');
}

set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});

spl_autoload_register(function(string $class) use($baseDir){
    $file = $baseDir.'/'. str_replace('\\', '/', $class) . '.php';
    
    if(is_file($file)){
        include $file;
    }
});

$xml = simplexml_load_file($baseDir.'/web.xml');

if(isset($xml->dependencies->name)){
    foreach($xml->dependencies->name as $dependency){
        include $baseDir . '/lib/' . (string)$dependency;
    }
}

$app = new \Core\Web\Application();
try{
    $app->run($baseDir, new \Core\Configuration\ConfigurationManager($xml));
}catch(\Exception $e){
    $app->error($e);
}