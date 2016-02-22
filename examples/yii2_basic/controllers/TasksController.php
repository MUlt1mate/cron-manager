<?php
namespace app\controllers;

use app\assets\TasksAsset;
use app\models\Task;
use app\models\TaskRun;
use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskLoader;
use mult1mate\crontab\TaskRunner;
use mult1mate\crontab\TaskManager;
use yii\web\Controller;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class TasksController extends Controller
{
    private static $tasks_controllers_folder;
    private static $tasks_namespace;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        self::$tasks_controllers_folder = __DIR__ . '/../models/';
        self::$tasks_namespace = 'app\\models\\';
        TasksAsset::register($this->view);
    }

    public function actionIndex()
    {
        return $this->render('tasks_list', array(
            'tasks' => Task::getList(),
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder, self::$tasks_namespace),
        ));
    }


    public function actionExport()
    {
        return $this->render('export', array());
    }

    public function actionParseCrontab()
    {
        if (isset($_POST['crontab'])) {
            $result = TaskManager::parseCrontab($_POST['crontab'], new Task());
            echo json_encode($result);
        }
    }

    public function actionExportTasks()
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

    public function actionTaskLog()
    {
        $task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;
        $runs = TaskRun::getLast($task_id);
        return $this->render('runs_list', array('runs' => $runs));
    }

    public function actionRunTask()
    {
        if (isset($_POST['task_id'])) {
            $tasks = !is_array($_POST['task_id']) ? array($_POST['task_id']) : $_POST['task_id'];
            foreach ($tasks as $t) {
                $task = Task::findOne($t);
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

    public function actionGetDates()
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

    public function actionGetOutput()
    {
        if (isset($_POST['task_run_id'])) {
            $run = TaskRun::findOne($_POST['task_run_id']);
            /**
             * @var TaskRun $run
             */

            echo htmlentities($run->getOutput());
        } else {
            echo 'empty task run id';
        }
    }

    public function actionTaskEdit()
    {
        if (isset($_GET['task_id'])) {
            $task = Task::findOne($_GET['task_id']);
        } else {
            $task = new Task();
        }
        /**
         * @var Task $task
         */
        $post = \Yii::$app->request->post();
        if ($task->load($post) && $task->validate()) {
            $task = TaskManager::editTask(
                $task,
                $post['Task']['time'],
                $post['Task']['command'],
                $post['Task']['status'],
                $post['Task']['comment']
            );
            \Yii::$app->response->redirect('/?r=tasks/task-edit&task_id=' . $task->task_id);
        }

        return $this->render('task_edit', array(
            'task' => $task,
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder, self::$tasks_namespace),
        ));
    }

    public function actionTasksUpdate()
    {
        if (isset($_POST['task_id'])) {
            $tasks = Task::findAll($_POST['task_id']);
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

    public function actionTasksReport()
    {
        $date_begin = isset($_GET['date_begin']) ? $_GET['date_begin'] : date('Y-m-d', strtotime('-6 day'));
        $date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d');

        return $this->render('report', array(
            'report' => Task::getReport($date_begin, $date_end),
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ));
    }
}
