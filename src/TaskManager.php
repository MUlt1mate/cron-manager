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
    /**
     * @param TaskInterface $task
     * @param string $time_expression
     * @param string $command
     * @param string $comment
     * @return TaskInterface
     */
    public static function editTask($task, $time_expression, $command, $comment = null)
    {
        $task->setStatus(TaskInterface::TASK_STATUS_ACTIVE);
        $task->setCommand($command);
        $task->setTime($time_expression);
        if (isset($comment))
            $task->setComment($comment);

        $task->taskSave();
        return $task;
    }

    public static function checkTasks($tasks)
    {
        foreach ($tasks as $t) {
            /**
             * @var TaskInterface $t
             */

            $cron = CronExpression::factory($t->getTime());
            if ($cron->isDue())
                self::runTask($t);
        }
    }

    public static function getRunDates($time)
    {
        try {
            $cron = CronExpression::factory($time);
            $dates = $cron->getMultipleRunDates(10);
        } catch (\Exception $e) {
            return [];
        }
        return $dates;
    }

    /**
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
        if (!$result)
            $run_final_status = TaskRunInterface::RUN_STATUS_ERROR;

        $output = ob_get_clean();
        $run->setOutput($output);

        $time_end = microtime(true);
        $time = round(($time_end - $time_begin), 2);
        $run->setExecutionTime($time);

        $run->setStatus($run_final_status);
        $run->saveTaskRun();
        return $output;
    }

    public static function parseAndRunCommand($command)
    {
        try {
            preg_match('/(\w+)::(\w+)\((.*)\)/', $command, $match);
            list(, $class, $method, $args) = $match;
            if (!class_exists($class))
                throw new CrontabManagerException('class ' . $class . ' not found');

            $obj = new $class();
            if (!method_exists($obj, $method))
                throw new CrontabManagerException('method ' . $method . ' not found in class ' . $class);

            $result = call_user_func_array([$obj, $method], self::prepare_args($args));
        } catch (\Exception $e) {
            echo ' Caught an exception: ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
            return false;
        }
        return $result;
    }

    protected static function prepare_args($args)
    {
        $args = explode(',', $args);
        return array_map(function ($a) {
            return trim($a);
        }, $args);
    }

    public static function getControllerMethods($class)
    {
        if (!class_exists($class))
            throw new CrontabManagerException('class ' . $class . ' not found');
        $class_methods = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $parent_class_methods = get_class_methods($parent_class);
            $result_methods = array_diff($class_methods, $parent_class_methods);
        } else {
            $result_methods = $class_methods;
        }
        return ($result_methods);
    }

    public static function getControllersList($paths)
    {
        $controllers = [];
        foreach ($paths as $p) {
            if (!file_exists($p))
                throw new CrontabManagerException('folder ' . $p . ' does not exist');
            $files = scandir($p);
            foreach ($files as $f) {
                if (preg_match('/^([A-Z]\w+)\.php$/', $f, $match))
                    $controllers[] = $match[1];
            }
        }
        return $controllers;
    }

    public static function getAllMethods($folder)
    {
        if (!is_array($folder))
            $folder = [$folder];
        $methods = [];
        $controllers = self::getControllersList($folder);
        foreach ($controllers as $c) {
            $methods[$c] = self::getControllerMethods($c);
        }

        return $methods;
    }
}