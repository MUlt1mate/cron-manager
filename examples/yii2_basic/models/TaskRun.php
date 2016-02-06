<?php
namespace app\models;

use mult1mate\crontab\TaskRunInterface;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 21:12
 * @property int $task_run_id
 * @property int $task_id
 * @property string $status
 * @property string $output
 * @property int $execution_time
 * @property string $ts
 */
class TaskRun extends ActiveRecord implements TaskRunInterface
{
    public static function tableName()
    {
        return 'task_runs';
    }

    public static function getLast($task_id = null, $count = 100)
    {
        $db = (new Query())
            ->select('task_runs.*, tasks.command')
            ->from(self::tableName())
            ->join('LEFT JOIN', 'tasks', 'tasks.task_id = task_runs.task_id')
            ->orderBy('task_runs.task_run_id desc')
            ->limit($count);
        if ($task_id) {
            $db->where('task_runs.task_id=:task_id', array(':task_id' => $task_id));
        }

        return $db->all();
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
