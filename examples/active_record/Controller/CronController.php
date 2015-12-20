<?php
use mult1mate\crontab\TaskManager;

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class CronController extends BaseController
{

    public function index()
    {

    }

    public function task()
    {
//        $task = new Task();
//        $task = ActiveTaskManager::editTask($task, '* * * * *', 'ActiveController::simple_task');
        $task = Task::task_get(1);
        echo $task->getCommand();
    }


    public function check_tasks()
    {
        TaskManager::check_tasks(Task::get_all());
    }

}