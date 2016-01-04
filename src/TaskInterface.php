<?php
/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 13:25
 */

namespace mult1mate\crontab;


/**
 * Interface TaskInterface
 * @package mult1mate\crontab
 */
interface TaskInterface
{
    const TASK_STATUS_ACTIVE = 'active';
    const TASK_STATUS_INACTIVE = 'inactive';

    public static function taskGet($task_id);

    public static function getAll();

    public function taskDelete();

    public function taskSave();

    /**
     * @return TaskInterface
     */
    public static function createNew();

    /**
     * @return TaskRunInterface
     */
    public function createTaskRun();

    /**
     * @return int
     */
    public function getTaskId();

    /**
     * @return string
     */
    public function getTime();

    /**
     * @param string $time
     */
    public function setTime($time);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     */
    public function setComment($comment);

    /**
     * @return string
     */
    public function getCommand();

    /**
     * @param string $command
     */
    public function setCommand($command);

    /**
     * @return string
     */
    public function getTs();

    /**
     * @param string $ts
     */
    public function setTs($ts);

    /**
     * @return string
     */
    public function getTsUpdated();

    /**
     * @param string $ts
     */
    public function setTsUpdated($ts);
}