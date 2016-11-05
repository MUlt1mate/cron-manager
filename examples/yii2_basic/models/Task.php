<?php

namespace app\models;

use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskRunInterface;
use yii\db\ActiveRecord;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:54
 * @property int $task_id
 * @property string $time
 * @property string $command
 * @property string $status
 * @property string $comment
 * @property string $ts
 * @property string $ts_updated
 */
class Task extends ActiveRecord implements TaskInterface
{
    public static function tableName()
    {
        return 'tasks';
    }

    public static function taskGet($task_id)
    {
        return self::findOne($task_id);
    }

    public static function getList()
    {
        return self::findBySql("SELECT * FROM `tasks`
        WHERE `status` NOT IN('deleted')
        ORDER BY status, task_id DESC")->all();
    }

    public static function getAll()
    {
        return self::find()->all();
    }

    public static function getReport($date_begin, $date_end)
    {
        $sql = "SELECT t.command, t.task_id,
        SUM(CASE WHEN tr.status = 'started' THEN 1 ELSE 0 END) AS started,
        SUM(CASE WHEN tr.status = 'completed' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN tr.status = 'error' THEN 1 ELSE 0 END) AS error,
        round(AVG(tr.execution_time),2) AS time_avg,
        count(*) AS runs
        FROM task_runs AS tr
        LEFT JOIN tasks AS t ON t.task_id=tr.task_id
        WHERE tr.ts BETWEEN :date_begin AND :date_end + INTERVAL 1 DAY
        GROUP BY command
        ORDER BY tr.task_id";
        return \Yii::$app->db->createCommand($sql, array(
            ':date_begin' => $date_begin,
            ':date_end' => $date_end
        ))->queryAll();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'command', 'status'], 'required'],
            [['time', 'status'], 'string', 'max' => 64],
            [['command'], 'string', 'max' => 256],
        ];
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
        return new self();
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
