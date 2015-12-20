<?php
use mult1mate\crontab\TaskManager;

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class CronController extends BaseController
{
    public function __construct()
    {
        require_once 'views/template.php';
        parent::__construct();
    }

    public function index()
    {
        $this->renderView('tasks_list', ['tasks' => Task::getAll()]);
    }

    public function taskLog()
    {
        $runs = TaskRun::all(['task_id' => $_GET['task_id']]);
        $this->renderView('runs_list', ['runs' => $runs]);
    }

    public function taskEdit()
    {
        if (isset($_GET['task_id']))
            $task = Task::find($_GET['task_id']);
        else
            $task = new Task();
        /**
         * @var Task $task
         */
        if (!empty($_POST)) {
            $task->set_attributes($_POST);
            $task->save();
        }

        $this->renderView('task_edit', ['task' => $task]);
    }


    public function checkTasks()
    {
        TaskManager::checkTasks(Task::getAll());
    }

}