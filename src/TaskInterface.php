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

    public static function task_get($task_id);

    public static function get_all();

    public function task_delete();

    public function task_save();

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
}