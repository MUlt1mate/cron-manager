<?php
namespace mult1mate\crontab;

use Cron\CronExpression;
use Cron\FieldFactory;

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
        $fieldFactory = new FieldFactory();
        foreach ($tasks as $t) {
            /**
             * @var TaskInterface $t
             */

            $cron = new CronExpression($t->getTime(), $fieldFactory);
            if ($cron->isDue())
                self::runTask($t);
        }
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

        $command = $task->getCommand();
        ob_start();
        $time_begin = microtime(true);
        try {
            self::parseAndRunCommand($command);
        } catch (\Exception $e) {
            echo ' Caught an exception: ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
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

    protected static function parseAndRunCommand($command)
    {
        list($class, $method) = explode('::', $command);
        if (!class_exists($class))
            throw new CrontabManagerException('class ' . $class . ' not found');

        //static::load_class($class);
        $obj = new $class();
        if (!method_exists($obj, $method))
            throw new CrontabManagerException('method ' . $method . ' not found in class ' . $class);
        $obj->$method();
    }
}