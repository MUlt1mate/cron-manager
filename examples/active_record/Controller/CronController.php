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
        $this->renderView('tasks_list', ['tasks' => Task::getAll()]);
    }

    public function taskLog()
    {
        $task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;
        $runs = TaskRun::getLast($task_id);
        $this->renderView('runs_list', ['runs' => $runs]);
    }

    public function runTask()
    {
        if (isset($_POST['task_id'])) {
            $task = Task::find($_POST['task_id']);
            /**
             * @var Task $task
             */

            $output = TaskManager::runTask($task);
            echo htmlentities($output);
        } else
            echo 'empty task id';
    }

    public function getOutput()
    {
        if (isset($_POST['task_run_id'])) {
            $run = TaskRun::find($_POST['task_run_id']);
            /**
             * @var TaskRun $run
             */

            echo htmlentities($run->getOutput());
        } else
            echo 'empty task run id';
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