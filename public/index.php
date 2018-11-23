<?php

$baseDir = dirname(__DIR__);

$p = new Phar($baseDir . '/lib/vis-core.phar');
$p->buildFromDirectory($baseDir . '/lib/vis-core');
$p->setDefaultStub('index.php', 'index.php');

$p2 = new Phar($baseDir . '/lib/vis-mvc.phar');
$p2->buildFromDirectory($baseDir . '/lib/vis-mvc');
$p2->setDefaultStub('index.php', 'index.php');


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
$app = new \Core\Web\Application($baseDir, new \Core\Web\Configuration($xml));
try{
    $app->run();
}catch(\Exception $e){
    $app->error($e);
}