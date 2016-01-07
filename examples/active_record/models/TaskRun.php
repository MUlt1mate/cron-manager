<?php
use ActiveRecord\Model;
use mult1mate\crontab\TaskRunInterface;

/**
 * User: mult1mate
 * Date: 20.12.15
 * Time: 21:12
 * @property int $task_run_id
 * @property int $task_id
 * @property string $status
 * @property string $output
 * @property int $execution_time
 * @property Task $task
 * @property \ActiveRecord\DateTime $ts
 */
class TaskRun extends Model implements TaskRunInterface
{
    static $belongs_to = [
        ['task']
    ];

    public static function getLast($task_id = null, $count = 100)
    {
        $conditions = ['order' => 'task_run_id desc', 'include' => ['task'], 'limit' => $count];
        if ($task_id)
            $conditions['conditions'] = ['task_id' => $task_id];

        return self::find('all', $conditions);
    }

    public function saveTaskRun()
    {
        return $this->save();
    }

    /**
     * @return int
     */
    public function getTaskRunId()
    {
        return $this->task_run_id;
    }

    /**
     * @return int
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @param int $task_id
     */
    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getExecutionTime()
    {
        return $this->execution_time;
    }

    /**
     * @param int $execution_time
     */
    public function setExecutionTime($execution_time)
    {
        $this->execution_time = $execution_time;
    }

    /**
     * @return string
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param string $ts
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }
}