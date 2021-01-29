<?php
$query = $_SERVER['QUERY_STRING'];
$query = ltrim($query, '/');
use vendor\core\Router;
error_reporting(-1);


require '../vendor/libs/functions.php';
require '../vendor/libs/jwtconf.php';

require '../vendor/firebase/php-jwt/src/JWT.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
define('DOMAIN','http://fw.loc');
define('WWW', __DIR__);
define('JWTCONF', dirname(__DIR__) . '/vendor/libs/jwtconf.php');
define('CORE',dirname(__DIR__) . '/vendor/core');
define('ROOT',dirname(__DIR__));
define('APP',dirname(__DIR__) . '/app');
define('LAYOUT', 'default');

spl_autoload_register(function($class){
    $file = ROOT . '/' . str_replace('\\','/',$class) . '.php';
    if(is_file($file)){
        require_once $file;
//        echo $file;
    }
});
Router::add('^page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$',['controller' =>'Page']);
Router::add('^page/(?P<alias>[a-z-]+)$',['controller' =>'Page','action' => 'view' ]);
Router::add('^$',['controller' =>'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');




Router::dispatch($query );