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
     */
    protected static function runTask($task)
    {
        $run = $task->createTaskRun();
        $run->setTaskId($task->getTaskId());
        $run->setTs(date('Y-m-d H:i:s'));
        $run->setStatus(TaskRunInterface::RUN_STATUS_STARTED);
        $run->saveTaskRun();
        $command = $task->getCommand();
        self::parseAndRunCommand($command);
        $run->setStatus(TaskRunInterface::RUN_STATUS_COMPLETED);
        $run->saveTaskRun();
    }

    protected static function parseAndRunCommand($command)
    {
        $names = explode('::', $command);
        $class = $names[0];
        $method = $names[1];
//        if (!class_exists($class))
//            static::load_class($class);
        $obj = new $class();
        $obj->$method();
    }
}