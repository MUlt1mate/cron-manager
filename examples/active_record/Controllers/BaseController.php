<?php

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 22:19
 */
class BaseController
{
    public function __construct()
    {
        ActiveRecord\Config::initialize(function ($cfg) {
            $cfg->set_model_directory('models');
            $cfg->set_connections([
                'development' => 'mysql://root:qwerty@localhost/crontab']);
        });
    }

    protected function renderView($view, $params, $template = true)
    {
        if ($template)
            require_once 'views/template.php';
        foreach ($params as $key => $value)
            $$key = $value;
        require_once "views/$view.php";
    }

}