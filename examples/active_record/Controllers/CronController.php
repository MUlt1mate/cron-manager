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
        $this->renderView('tasks_list', [
            'tasks' => Task::getAll(),
            'methods' => TaskManager::getAllMethods(__DIR__),
        ]);
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
            echo($output);
            //            echo htmlentities($output);
        } elseif (isset($_POST['custom_task'])) {
            $result = TaskManager::parseAndRunCommand($_POST['custom_task']);
            echo ($result) ? ' success' : ' failed';
        } else
            echo 'empty task id';
    }

    public function getDates()
    {
        $time = $_POST['time'];
        $dates = TaskManager::getRunDates($time);
        echo '<ul>';
        foreach ($dates as $d)
            /**
             * @var \DateTime $d
             */
            echo '<li>' . $d->format('Y-m-d H:i:s') . '</li>';
        echo '</ul>';
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

        $this->renderView('task_edit', [
            'task' => $task,
            'methods' => TaskManager::getAllMethods(__DIR__),
        ]);
    }


    public function checkTasks()
    {
        TaskManager::checkTasks(Task::getAll());
    }

}