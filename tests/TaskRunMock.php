<?php
namespace mult1mate\crontab_tests;

use mult1mate\crontab\TaskRunInterface;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 10:12
 */
class TaskRunMock implements TaskRunInterface
{
    private $task_run_id;
    private $task_id;
    private $status;
    private $output;
    private $execution_time;
    private $timestamp;

    public function saveTaskRun()
    {
        return true;
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
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTs($timestamp)
    {
        $this->timestamp = $timestamp;
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