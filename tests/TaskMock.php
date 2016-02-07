<?php
namespace mult1mate\crontab_tests;

use mult1mate\crontab\TaskInterface;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 10:07
 */
class TaskMock implements TaskInterface
{
    private $task_id;
    private $time;
    private $command;
    private $status;
    private $comment;
    private $timestamp;
    private $ts_updated;

    public static function taskGet($task_id)
    {
        return new self();
    }

    public static function getAll()
    {
        return array();
    }

    public function taskDelete()
    {
        return true;
    }

    public function taskSave()
    {
        return true;
    }

    /**
     * @return TaskInterface
     */
    public static function createNew()
    {
        return new self();
    }

    /**
     * @return \mult1mate\crontab\TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRunMock();
    }

    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getTs()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTs($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTsUpdated()
    {
        return $this->ts_updated;
    }

    /**
     * @param mixed $ts
     */
    public function setTsUpdated($ts)
    {
        $this->ts_updated = $ts;
    }
}
