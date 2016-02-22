<?php
//composer autoload
require_once '../vendor/autoload.php';
//application autoload
spl_autoload_register(function ($class_name) {
    if (file_exists('../Controllers/' . $class_name . '.php')) {
        require_once '../Controllers/' . $class_name . '.php';
    }
});

//db config
$db_user = 'root';
$db_pass = 'qwerty';
$db_name = 'crontab';

//define controller and method
$controller = 'tasks';
$method = 'index';
if (isset($argv) && 2 < count($argv)) {
    $controller = $argv[1];
    $method = $argv[2];
}
if (isset($_GET['c'])) {
    $controller = $_GET['c'];
}
if (isset($_GET['m'])) {
    $method = $_GET['m'];
}
$controller_class = ucfirst($controller) . 'Controller';
$c = new $controller_class($db_user, $db_pass, $db_name);
$c->$method();
