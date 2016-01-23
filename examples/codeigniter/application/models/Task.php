<?php
use mult1mate\crontab\DbHelper;
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
 * @property array $taskruns
 * @property string $ts
 * @property string $ts_updated
 */
class Task extends DbBaseModel implements TaskInterface
{
    public static $primary_key = 'task_id';
    public static $table_name = 'tasks';

    public function attributes()
    {
        return ['task_id', 'time', 'command', 'status', 'comment', 'ts', 'ts_updated'];
    }

    public static function taskGet($task_id)
    {
        return self::findByPk($task_id);
    }

    public static function getAll()
    {
        return self::findAll();
    }

    public static function getReport($date_begin, $date_end)
    {
        return self::getDb()->query(DbHelper::getReportSql(), [$date_begin, $date_end])->result();
    }

    public function taskDelete()
    {
        return $this->delete();
    }

    public function taskSave()
    {
        return $this->save();
    }

    public static function createNew()
    {
        $task = new self();
        $task->ts = date('Y-m-d H:i:s');
        return $task;
    }

    /**
     * @return TaskRunInterface
     */
    public function createTaskRun()
    {
        $this->load->model('TaskRun', 'task_run');
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
