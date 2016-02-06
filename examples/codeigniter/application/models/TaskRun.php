<?php
use mult1mate\crontab\TaskRunInterface;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 21:12
 * @property int $task_run_id
 * @property int $task_id
 * @property string $status
 * @property string $output
 * @property int $execution_time
 * @property Task $task
 * @property string $ts
 */
class TaskRun extends DbBaseModel implements TaskRunInterface
{
    public static $primary_key = 'task_run_id';
    public static $table_name = 'task_runs';

    public function attributes()
    {
        return array('task_run_id', 'task_id', 'status', 'output', 'execution_time', 'ts');
    }

    public static function getLast($task_id = null, $count = 100)
    {
        $sql = "SELECT tr.*, t.command
        FROM task_runs AS tr
        LEFT JOIN tasks AS t ON t.task_id=tr.task_id ";
        if ($task_id) {
            $sql .= " WHERE tr.task_id = " . (int)$task_id;
        }
        $sql .= "
        ORDER BY task_run_id DESC
        LIMIT " . (int)$count;

        return self::getDb()->query($sql)->result();
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