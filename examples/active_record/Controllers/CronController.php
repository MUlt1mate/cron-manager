<?php
use mult1mate\crontab\TaskInterface;
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

    public function export()
    {
        $this->renderView('export', []);
    }

    public function parseCrontab()
    {
        if (isset($_POST['crontab'])) {
            $result = TaskManager::parseCrontab($_POST['crontab'], new Task());
            echo json_encode($result);
        }
    }

    public function importTasks()
    {
        if (isset($_POST['folder'])) {
            $tasks = Task::getAll();
            $result = [];
            foreach ($tasks as $t) {
                $line = TaskManager::getTaskCrontabLine($t, $_POST['folder'], $_POST['php'], $_POST['file']);
                $result[] = nl2br($line);
            }
            echo json_encode($result);
        }
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
            $tasks = !is_array($_POST['task_id']) ? [$_POST['task_id']] : $_POST['task_id'];
            foreach ($tasks as $t) {
                $task = Task::find($t);
                /**
                 * @var Task $task
                 */

                $output = TaskManager::runTask($task);
                echo($output . '<hr>');
                //            echo htmlentities($output);
            }
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
            $task = TaskManager::editTask($task, $_POST['time'], $_POST['command'], $_POST['status'], $_POST['comment']);
        }

        $this->renderView('task_edit', [
            'task' => $task,
            'methods' => TaskManager::getAllMethods(__DIR__),
        ]);
    }

    public function tasksUpdate()
    {
        if (isset($_POST['task_id'])) {
            $tasks = Task::find($_POST['task_id']);
            foreach ($tasks as $t) {
                /**
                 * @var Task $t
                 */
                $status = ('Enable' == $_POST['action']) ? TaskInterface::TASK_STATUS_ACTIVE : TaskInterface::TASK_STATUS_INACTIVE;
                $t->setStatus($status);
                $t->save();
            }
        }
    }

    public function checkTasks()
    {
        TaskManager::checkTasks(Task::getAll());
    }

}