<?php
use mult1mate\crontab\TaskInterface;

/**
 * Model that stores task information in file
 * @author mult1mate
 * Date: 22.02.16
 * Time: 17:33
 */
class TaskFile implements TaskInterface
{
    protected $task_id;
    protected $command;
    protected $time;
    protected $status;
    protected $comment;
    protected $ts_created;
    protected $ts_updated;

    /**
     * Data file. Creates if not exists.
     * @var string
     */
    protected static $data_file = '/tmp/crontasks';

    /**
     * Reads file from disk and returns array of tasks
     * @return array
     */
    public static function taskFileLoad()
    {
        if (file_exists(self::$data_file)) {
            $data = file_get_contents(self::$data_file);
            $data = unserialize($data);
        }
        if (empty($data)) {
            $data = array();
        }

        return $data;
    }

    /**
     * Writes tasks into file
     * @param array $tasks
     * @return bool
     */
    public static function taskFileSave($tasks)
    {
        $save = file_put_contents(self::$data_file, serialize($tasks));
        return (0 < $save);
    }

    /**
     * Returns tasks with given id
     * @param int $task_id
     * @return TaskInterface
     */
    public static function taskGet($task_id)
    {
        $tasks = self::taskFileLoad();
        if (isset($tasks[$task_id])) {
            return $tasks[$task_id];
        }
        return false;
    }

    /**
     * Returns array of all tasks
     * @return array
     */
    public static function getAll()
    {
        return self::taskFileLoad();
    }

    /**
     * Returns array of tasks with specified ids
     * @param array $task_ids array of task ids
     * @return array
     */
    public static function find($task_ids)
    {
        if (!is_array($task_ids)) {
            $task_ids = array($task_ids);
        }
        $tasks = self::getAll();
        foreach ($tasks as $key => $task) {
            /**
             * @var self $task
             */
            if (!in_array($task->task_id, $task_ids)) {
                unset($tasks[$key]);
            }
        }
        return $tasks;
    }

    /**
     * Deletes the task
     * @return mixed
     */
    public function taskDelete()
    {
        $tasks = self::taskFileLoad();

        if (isset($tasks[$this->task_id])) {
            unset($tasks[$this->task_id]);

            return self::taskFileSave($tasks);
        }
        return false;
    }

    /**
     * Saves the task
     * @return mixed
     */
    public function taskSave()
    {
        $taskId = $this->getTaskId();
        $tasks = self::taskFileLoad();
        if (empty($taskId)) {
            $task_ids = array_keys($tasks);
            $this->setTaskId(array_pop($task_ids) + 1);
            $this->setTs(date('Y-m-d H:i:s'));
        }

        $tasks[$this->getTaskId()] = $this;
        return $this->taskFileSave($tasks);
    }

    /**
     * Creates new task object and returns it
     * @return TaskInterface
     */
    public static function createNew()
    {
        return new self();
    }

    /**
     * Creates new task run object for current task and returns it
     * @return \mult1mate\crontab\TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRunFile();
    }

    /**
     * @return mixed
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
        return $this->ts_created;
    }

    /**
     * @param mixed $ts
     */
    public function setTs($ts)
    {
        $this->ts_created = $ts;
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
