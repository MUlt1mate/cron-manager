<?php
use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskManager;
use mult1mate\crontab\TaskRunner;
use mult1mate\crontab\TaskLoader;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:56
 * @property Task $task
 * @property TaskRun $task_run
 */
class TasksController extends CI_Controller
{
    private $controller_folder;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('DbBaseModel');
        $this->load->model('Task', 'task');
        $this->load->model('TaskRun', 'task_run');
        $this->load->helper('url');
        $this->controller_folder = __DIR__ . '/../models';

        TaskLoader::setClassFolder($this->controller_folder);
    }

    public function index()
    {
        $this->load->view('tasks/tasks_list', array(
            'tasks' => Task::findAll(array('not_in' => array('status', TaskInterface::TASK_STATUS_DELETED))),
            'methods' => TaskLoader::getAllMethods($this->controller_folder),
        ));
    }

    public function export()
    {
        $this->load->view('tasks/export', array());
    }

    public function parseCrontab()
    {
        if (isset($_POST['crontab'])) {
            $result = TaskManager::parseCrontab($_POST['crontab'], new Task());
            echo json_encode($result);
        }
    }

    public function exportTasks()
    {
        if (isset($_POST['folder'])) {
            $tasks = Task::findAll(
                array('in' => array(
                    'status',
                    array(TaskInterface::TASK_STATUS_ACTIVE, TaskInterface::TASK_STATUS_INACTIVE)
                ))
            );
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
        $this->load->view('tasks/runs_list', array('runs' => $runs));
    }

    public function runTask()
    {
        if (isset($_POST['task_id'])) {
            $tasks = !is_array($_POST['task_id']) ? array($_POST['task_id']) : $_POST['task_id'];
            foreach ($tasks as $t) {
                $task = Task::findByPk($t);
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
            $run = TaskRun::findByPk($_POST['task_run_id']);
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
            $task = Task::findByPk($_GET['task_id']);
        } else {
            $task = Task::createNew();
        }
        /**
         * @var Task $task
         */
        if (!empty($_POST)) {
            $task = TaskManager::editTask($task, $_POST['time'], $_POST['command'], $_POST['status'], $_POST['comment']);
        }

        $this->load->view('tasks/task_edit', array(
            'task' => $task,
            'methods' => TaskLoader::getAllMethods($this->controller_folder),
        ));
    }

    public function tasksUpdate()
    {
        if (isset($_POST['task_id'])) {
            $tasks = Task::findAll(array('in' => array('task_id', $_POST['task_id'])));
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
                $t->taskSave();
            }
        }
    }

    public function checkTasks()
    {
        TaskRunner::checkAndRunTasks($this->task->getAll());
    }

    public function tasksReport()
    {
        $date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : date('Y-m-d', strtotime('-6 day'));
        $date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d');

        $this->load->view('tasks/report', array(
            'report' => Task::getReport($date_begin, $date_end),
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ));
    }
}
