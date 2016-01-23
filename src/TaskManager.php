<?php
namespace mult1mate\crontab;

use Cron\CronExpression;

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 12:55
 */
class TaskManager
{
    const SETTING_LOAD_CLASS = 'load_class';
    const SETTING_CLASS_FOLDERS = 'class_folders';
    protected static $load_class = false;
    protected static $class_folders = [];

    /**
     * @param TaskInterface $task
     * @param string $time
     * @param string $command
     * @param string $status
     * @param string $comment
     * @return TaskInterface
     */
    public static function editTask($task, $time, $command, $status = TaskInterface::TASK_STATUS_ACTIVE, $comment = null)
    {
        $task->setStatus($status);
        $task->setCommand(self::validateCommand($command));
        $task->setTime($time);
        if (isset($comment)) {
            $task->setComment($comment);
        }

        $task->setTsUpdated(date('Y-m-d H:i:s'));

        $task->taskSave();
        return $task;
    }

    /**
     * @param string $command
     * @return string
     * @throws CrontabManagerException
     */
    public static function validateCommand($command)
    {
        list($class, $method, $args) = self::parseCommand($command);
        $args = array_map(function ($elem) {
            return trim($elem);
        }, $args);
        return $class . '::' . $method . '(' . trim(implode(',', $args), ',') . ')';
    }

    /**
     * Runs active tasks if current time matches with time expression
     * @param array $tasks
     */
    public static function checkAndRunTasks($tasks)
    {
        $date = date('Y-m-d H:i:s');
        foreach ($tasks as $t) {
            /**
             * @var TaskInterface $t
             */
            if (TaskInterface::TASK_STATUS_ACTIVE != $t->getStatus()) {
                continue;
            }

            $cron = CronExpression::factory($t->getTime());

            if ($cron->isDue($date)) {
                self::runTask($t);
            }
        }
    }

    /**
     * Returns next run dates for time expression
     * @param string $time
     * @param int $count
     * @return array
     */
    public static function getRunDates($time, $count = 10)
    {
        try {
            $cron = CronExpression::factory($time);
            $dates = $cron->getMultipleRunDates($count);
        } catch (\Exception $e) {
            return [];
        }
        return $dates;
    }

    /**
     * Runs task and returns output
     * @param TaskInterface $task
     * @return string
     */
    public static function runTask($task)
    {
        $run = $task->createTaskRun();
        $run->setTaskId($task->getTaskId());
        $run->setTs(date('Y-m-d H:i:s'));
        $run->setStatus(TaskRunInterface::RUN_STATUS_STARTED);
        $run->saveTaskRun();
        $run_final_status = TaskRunInterface::RUN_STATUS_COMPLETED;

        ob_start();
        $time_begin = microtime(true);

        $result = self::parseAndRunCommand($task->getCommand());
        if (!$result) {
            $run_final_status = TaskRunInterface::RUN_STATUS_ERROR;
        }

        $output = ob_get_clean();
        $run->setOutput($output);

        $time_end = microtime(true);
        $time = round(($time_end - $time_begin), 2);
        $run->setExecutionTime($time);

        $run->setStatus($run_final_status);
        $run->saveTaskRun();
        return $output;
    }

    /**
     * @param string $command
     * @return mixed
     */
    public static function parseAndRunCommand($command)
    {
        try {
            list($class, $method, $args) = self::parseCommand($command);
            if (!class_exists($class)) {
                self::loadController($class);
            }

            $obj = new $class();
            if (!method_exists($obj, $method)) {
                throw new CrontabManagerException('method ' . $method . ' not found in class ' . $class);
            }

            $result = call_user_func_array([$obj, $method], $args);
        } catch (\Exception $e) {
            echo 'Caught an exception: ' . get_class($e) . ': ' . PHP_EOL . $e->getMessage() . PHP_EOL;
            return false;
        }
        return $result;
    }

    /**
     * @param string $command
     * @return array
     * @throws CrontabManagerException
     */
    protected static function parseCommand($command)
    {
        if (preg_match('/(\w+)::(\w+)\((.*)\)/', $command, $match)) {
            return [
                $match[1],
                $match[2],
                explode(',', $match[3])
            ];
        } else {
            throw new CrontabManagerException('Command not recognized');
        }
    }

    protected static function loadController($class_name)
    {
        if (self::$load_class) {
            foreach (self::$class_folders as $f) {
                $f = rtrim($f, '/');
                $filename = $f . '/' . $class_name . '.php';
                if (file_exists($filename)) {
                    require_once $filename;
                    if (class_exists($class_name)) {
                        return true;
                    }
                }
            }
        }

        throw new CrontabManagerException('class ' . $class_name . ' not found');
    }

    /**
     * Returns all public methods for class
     * @param string $class
     * @return array
     * @throws CrontabManagerException
     */
    public static function getControllerMethods($class)
    {
        if (!class_exists($class)) {
            throw new CrontabManagerException('class ' . $class . ' not found');
        }
        $class_methods = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $parent_class_methods = get_class_methods($parent_class);
            $result_methods = array_diff($class_methods, $parent_class_methods);
        } else {
            $result_methods = $class_methods;
        }
        return ($result_methods);
    }

    /**
     * Returns names of all php files in directories
     * @param array $paths
     * @return array
     * @throws CrontabManagerException
     */
    protected static function getControllersList($paths)
    {
        $controllers = [];
        foreach ($paths as $p) {
            if (!file_exists($p)) {
                throw new CrontabManagerException('folder ' . $p . ' does not exist');
            }
            $files = scandir($p);
            foreach ($files as $f) {
                if (preg_match('/^([A-Z]\w+)\.php$/', $f, $match)) {
                    $controllers[] = $match[1];
                }
            }
        }
        return $controllers;
    }

    /**
     * Scan folders for classes and return all their public methods
     * @param string|array $folder
     * @return array
     * @throws CrontabManagerException
     */
    public static function getAllMethods($folder)
    {
        if (!is_array($folder)) {
            $folder = [$folder];
        }
        $methods = [];
        $controllers = self::getControllersList($folder);
        foreach ($controllers as $c) {
            if (!class_exists($c)) {
                self::loadController($c);
            }
            $methods[$c] = self::getControllerMethods($c);
        }

        return $methods;
    }

    /**
     * @param string $cron
     * @param TaskInterface $task_class
     * @return array
     */
    public static function parseCrontab($cron, $task_class)
    {
        $cron_array = explode(PHP_EOL, $cron);
        $comment = null;
        $result = [];
        foreach ($cron_array as $c) {
            $c = trim($c);
            if (empty($c)) {
                continue;
            }
            $r = [];
            $r[] = $c;
            $cron_line_exp = '/(#?)(.*)cd.*php.*\.php\s+([\w\d-_]+)\s+([\w\d-_]+)\s*([\d\w-_\s]+)?(\d[\d>&\s]+)(.*)?/i';
            if (preg_match($cron_line_exp, $c, $matches)) {
                try {
                    CronExpression::factory($matches[2]);
                } catch (\Exception $e) {
                    $r[] = 'Time expression ' . $matches[2] . ' not valid';
                    $result[] = $r;
                    continue;
                }
                $task = $task_class::createNew();
                $task->setTime(trim($matches[2]));
                $arguments = str_replace(' ', ',', trim($matches[5]));
                $command = ucfirst($matches[3]) . '::' . $matches[4] . '(' . $arguments . ')';
                $task->setCommand($command);
                if (!empty($comment)) {
                    $task->setComment($comment);
                }
                $status = empty($matches[1]) ? TaskInterface::TASK_STATUS_ACTIVE : TaskInterface::TASK_STATUS_INACTIVE;
                $task->setStatus($status);
                $task->setTs(date('Y-m-d H:i:s'));
                $task->taskSave();
                //$output = $matches[7];
                $r [] = 'Saved';

                $comment = null;
            } elseif (preg_match('/#([\w\d\s]+)/i', $c, $matches)) {
                $comment = trim($matches[1]);
                $r [] = 'Looks like a comment';
            } else {
                $r [] = 'Not matched';
            }
            $result[] = $r;
        }

        return $result;
    }

    /**
     * Formats task line for export
     * @param TaskInterface $task
     * @param string $path
     * @param string $php_bin
     * @param string $input_file
     * @return string
     */
    public static function getTaskCrontabLine($task, $path, $php_bin, $input_file)
    {
        $str = '';
        $comment = $task->getComment();
        if (!empty($comment)) {
            $str .= '#' . $comment . PHP_EOL;
        }
        if (TaskInterface::TASK_STATUS_ACTIVE != $task->getStatus()) {
            $str .= '#';
        }
        list($class, $method, $args) = self::parseCommand($task->getCommand());
        $exec_cmd = $php_bin . ' ' . $input_file . ' ' . $class . ' ' . $method . ' ' . implode(' ', $args);
        $str .= $task->getTime() . ' cd ' . $path . '; ' . $exec_cmd . ' 2>&1 > /dev/null';
        return $str . PHP_EOL;
    }

    public static function setSetting($name, $value)
    {
        switch ($name) {
            case self::SETTING_CLASS_FOLDERS:
                if (is_array($value)) {
                    self::$class_folders = $value;
                } else {
                    self::$class_folders = [$value];
                }
                break;
            case self::SETTING_LOAD_CLASS:
                self::$load_class = (bool)$value;
                break;
            default:
                return false;
        }
        return true;
    }
}
