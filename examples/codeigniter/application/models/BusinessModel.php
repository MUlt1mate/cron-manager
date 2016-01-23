<?php

/**
 * User: mult1mate
 * Date: 23.01.16
 * Time: 12:37
 */
class BusinessModel extends CI_Model
{
    public function test()
    {
        $task = Task::findOne();
        print_r($task);
        return true;
    }
}
