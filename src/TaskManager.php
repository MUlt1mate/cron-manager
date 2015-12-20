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
     * @param null $comment
     * @return mixed
     */
    public static function createTask($task, $time_expression, $command, $comment = null)
    {
        return $task->create($time_expression, $command, $comment);
    }

    public static function check_tasks($tasks)
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
        $run->setTs(date('Y-m-d H:i:s'));
        $run->setStatus(TaskRunInterface::RUN_STATUS_STARTED);
        $run->save();
        $command = $task->getCommand();
        self::parseAndRunCommand($command);
        $run->setStatus(TaskRunInterface::RUN_STATUS_COMPLETED);
        $run->save();
    }

    protected static function parseAndRunCommand($command)
    {
        $names = explode('::', $command);
        $class = $names[0];
        $method = $names[1];
        $obj = new $class();
        $obj->$method();
    }
}