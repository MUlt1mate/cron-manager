<?php
require_once 'vendor/autoload.php';
require_once '../../vendor/autoload.php';
function __autoload($class_name)
{
    if (file_exists('Controllers/' . $class_name . '.php'))
        require_once 'Controllers/' . $class_name . '.php';
}

spl_autoload_register('__autoload');
new CronController();