<?php
namespace mult1mate\crontab;

use Cron\CronExpression;

/**
 * Class TaskManager
 * Contains methods for manipulate TaskInterface objects
 * @author mult1mate
 * @package mult1mate\crontab
 * Date: 20.12.15
 * Time: 12:55
 */
class TaskManager
{
    const CRON_LINE_REGEXP = '/(#?)(.*)cd.*php.*\.php\s+([\w\d-_]+)\s+([\w\d-_]+)\s*([\d\w-_\s]+)?(\d[\d>&\s]+)(.*)?/i';

    /**
     * Edit and save TaskInterface object
     * @param TaskInterface $task
     * @param string $time
     * @param string $command
     * @param string $status
     * @param string $comment
     * @return TaskInterface
     */
    public static function editTask($task, $time, $command, $status = TaskInterface::TASK_STATUS_ACTIVE, $comment = null)
    {
        if (!$validated_command = self::validateCommand($command)) {
            return $task;
        }
        $task->setStatus($status);
        $task->setCommand($validated_command);
        $task->setTime($time);
        if (isset($comment)) {
            $task->setComment($comment);
        }

        $task->setTsUpdated(date('Y-m-d H:i:s'));

        $task->taskSave();
        return $task;
    }

    /**
     * Checks if the command is correct and removes spaces
     * @param string $command
     * @return string|false
     */
    public static function validateCommand($command)
    {
        try {
            list($class, $method, $args) = self::parseCommand($command);
        } catch (TaskManagerException $e) {
            return false;
        }
        $args = array_map(function ($elem) {
            return trim($elem);
        }, $args);
        return $class . '::' . $method . '(' . trim(implode(',', $args), ',') . ')';
    }

    /**
     * Parses command and returns an array which contains class, method and arguments of the command
     * @param string $command
     * @return array
     * @throws TaskManagerException
     */
    public static function parseCommand($command)
    {
        if (preg_match('/([\w\\\\]+)::(\w+)\((.*)\)/', $command, $match)) {
            $params = explode(',', $match[3]);
            if ((1 == count($params)) && ('' == $params[0])) {
                //prevents to pass an empty string
                $params[0] = null;
            }
            return array(
                $match[1],
                $match[2],
                $params
            );
        }

        throw new TaskManagerException('Command not recognized');
    }

    /**
     * Parses each line of crontab content and creates new TaskInterface objects
     * @param string $cron
     * @param TaskInterface $task_class
     * @return array
     */
    public static function parseCrontab($cron, $task_class)
    {
        $cron_array = explode(PHP_EOL, $cron);
        $comment = null;
        $result = array();
        foreach ($cron_array as $c) {
            $c = trim($c);
            if (empty($c)) {
                continue;
            }
            $r = array($c);
            if (preg_match(self::CRON_LINE_REGEXP, $c, $matches)) {
                try {
                    CronExpression::factory($matches[2]);
                } catch (\Exception $e) {
                    $r[1] = 'Time expression is not valid';
                    $r[2] = $matches[2];
                    $result[] = $r;
                    continue;
                }
                $task = self::createTaskWithCrontabLine($task_class, $matches, $comment);

                $r [1] = 'Saved';
                $r [2] = $task;

                $comment = null;
            } elseif (preg_match('/#([\w\d\s]+)/i', $c, $matches)) {
                $comment = trim($matches[1]);
                $r [1] = 'Comment';
                $r [2] = $comment;
            } else {
                $r [1] = 'Not matched';
            }
            $result[] = $r;
        }

        return $result;
    }

    /**
     * Creates new TaskInterface object from parsed crontab line
     * @param TaskInterface $task_class
     * @param array $matches
     * @param string $comment
     * @return TaskInterface
     */
    private static function createTaskWithCrontabLine($task_class, $matches, $comment)
    {
        $task = $task_class::createNew();
        $task->setTime(trim($matches[2]));
        $arguments = str_replace(' ', ',', trim($matches[5]));
        $command = ucfirst($matches[3]) . '::' . $matches[4] . '(' . $arguments . ')';
        $task->setCommand($command);
        if (!empty($comment)) {
            $task->setComment($comment);
        }
        //$output = $matches[7];
        $status = empty($matches[1]) ? TaskInterface::TASK_STATUS_ACTIVE : TaskInterface::TASK_STATUS_INACTIVE;
        $task->setStatus($status);
        $task->setTs(date('Y-m-d H:i:s'));
        $task->taskSave();
        return $task;
    }

    /**
     * Formats task for export into crontab file
     * @param TaskInterface $task
     * @param string $path
     * @param string $php_bin
     * @param string $input_file
     * @return string
     * @throws TaskManagerException
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
}
