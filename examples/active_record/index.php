<?php
require_once 'vendor/autoload.php';
require_once '../../vendor/autoload.php';
function __autoload($class_name)
{
    require_once 'Controller/' . $class_name . '.php';
}

spl_autoload_register('__autoload');
new CronController();