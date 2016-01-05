<?php
require_once 'vendor/autoload.php';
require_once '../../vendor/autoload.php';
function __autoload($class_name)
{
    if (file_exists('Controllers/' . $class_name . '.php'))
        require_once 'Controllers/' . $class_name . '.php';
}

spl_autoload_register('__autoload');
$controller = 'tasks';
$method = 'index';

if (isset($argv) && 2 < count($argv)) {
    $controller = $argv[1];
    $method = $argv[2];
}
if (isset($_GET['c']))
    $controller = $_GET['c'];
if (isset($_GET['m']))
    $method = $_GET['m'];
$controller_class = ucfirst($controller) . 'Controller';
$c = new $controller_class();
$c->$method();