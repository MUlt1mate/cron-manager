<?php

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 22:19
 */
class BaseController
{
    public function __construct($db_user, $db_pass, $db_name, $server = 'localhost')
    {
        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory('../models');
        $cfg->set_connections(
            array(
                'development' => 'mysql://' . $db_user . ':' . $db_pass . '@' . $server . '/' . $db_name
            )
        );
    }

    protected function renderView($view, $params, $template = true)
    {
        if ($template) {
            require_once '../views/template.php';
        }
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        require_once "../views/$view.php";
    }
}
