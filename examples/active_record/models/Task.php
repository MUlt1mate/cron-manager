<?php
use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskRunInterface;

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 20:54
 * @property int $task_id
 * @property string $time
 * @property string $command
 * @property string $status
 * @property string $comment
 * @property string $ts
 */
class Task extends \ActiveRecord\Model implements TaskInterface
{

    public static function task_get($task_id)
    {
        return self::find($task_id);
    }

    public static function get_all()
    {
        return self::all();
    }

    public function task_delete()
    {
        return $this->delete();
    }

    public function task_save()
    {
        return $this->save();
    }

    /**
     * @return TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRun();
    }

    /**
     * @return mixed
     */
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
        return $this->ts;
    }

    /**
     * @param mixed $ts
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
    }
}