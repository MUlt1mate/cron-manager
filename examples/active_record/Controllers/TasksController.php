<?php
use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskLoader;
use mult1mate\crontab\TaskManager;
use mult1mate\crontab\TaskRunner;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class TasksController extends BaseController
{
    private static $tasks_controllers_folder = __DIR__;

    public function index()
    {
        $this->renderView('tasks_list', array(
            'tasks' => Task::getList(),
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder),
        ));
    }

    public function export()
    {
        $this->renderView('export', array());
    }

    public function parseCrontab()
    {
        if (isset($_POST['crontab'])) {
            $result_summon = $tasks = array();
            $result = TaskManager::parseCrontab($_POST['crontab'], new Task());
            foreach ($result as $r) {
                $result_summon[$r[1]] = (isset($result_summon[$r[1]])) ? $result_summon[$r[1]] + 1 : 1;
                if (isset($r[2]) && is_object($r[2])) {
                    $task = $r[2];
                    /**
                     * @var Task $task
                     */
                    $tasks[] = '#' . $task->getComment() . '<br>' . $task->getTime() . ' ' . $task->getCommand();
                }
            }
            echo '<h3>Results</h3>
                <b>';
            foreach ($result_summon as $value => $count) {
                echo $value . ': ' . $count . '<br>';
            }
            if (!empty($tasks)) {
                echo '<h4>Saved tasks</h4>';
            }
            echo '</b><code>' . implode('<hr>', $tasks) . '</code><hr>';
            echo '<h4>Not saved lines</h4>';
            foreach ($result as $r) {
                if (in_array($r[1], array('Not matched', 'Time expression is not valid'))) {
                    echo '<b>' . $r[1] . '</b><br>' . $r[0] . '<br>';
                }
            }
        }
    }

    public function exportTasks()
    {
        if (isset($_POST['folder'])) {
            $tasks = Task::getList();
            $result = array();
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
        $this->renderView('runs_list', array('runs' => $runs));
    }

    public function runTask()
    {
        if (isset($_POST['task_id'])) {
            $tasks = !is_array($_POST['task_id']) ? array($_POST['task_id']) : $_POST['task_id'];
            foreach ($tasks as $t) {
                $task = Task::find($t);
                /**
                 * @var Task $task
                 */

                $output = TaskRunner::runTask($task);
                echo($output . '<hr>');
            }
        } elseif (isset($_POST['custom_task'])) {
            $result = TaskRunner::parseAndRunCommand($_POST['custom_task']);
            echo ($result) ? 'success' : 'failed';
        } else {
            echo 'empty task id';
        }
    }

    public function getDates()
    {
        $time = $_POST['time'];
        $dates = TaskRunner::getRunDates($time);
        if (empty($dates)) {
            echo 'Invalid expression';
            return;
        }
        echo '<ul>';
        foreach ($dates as $d) {
            /**
             * @var \DateTime $d
             */
            echo '<li>' . $d->format('Y-m-d H:i:s') . '</li>';
        }
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
        } else {
            echo 'empty task run id';
        }
    }

    public function taskEdit()
    {
        if (isset($_GET['task_id'])) {
            $task = Task::find($_GET['task_id']);
        } else {
            $task = new Task();
        }
        /**
         * @var Task $task
         */
        if (!empty($_POST)) {
            $task = TaskManager::editTask($task, $_POST['time'], $_POST['command'], $_POST['status'], $_POST['comment']);
        }

        $this->renderView('task_edit', array(
            'task' => $task,
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder),
        ));
    }

    public function tasksUpdate()
    {
        if (isset($_POST['task_id'])) {
            $tasks = Task::find($_POST['task_id']);
            foreach ($tasks as $t) {
                /**
                 * @var Task $t
                 */
                $action_status = array(
                    'Enable' => TaskInterface::TASK_STATUS_ACTIVE,
                    'Disable' => TaskInterface::TASK_STATUS_INACTIVE,
                    'Delete' => TaskInterface::TASK_STATUS_DELETED,
                );
                $t->setStatus($action_status[$_POST['action']]);
                $t->save();
            }
        }
    }

    public function checkTasks()
    {
        TaskRunner::checkAndRunTasks(Task::getAll());
    }

    public function tasksReport()
    {
        $date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : date('Y-m-d', strtotime('-6 day'));
        $date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d');

        $this->renderView('report', array(
            'report' => Task::getReport($date_begin, $date_end),
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ));
    }
}
