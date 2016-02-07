<?php
namespace mult1mate\crontab;

/**
 * Interface TaskRunInterface
 * Common interface to handle task runs
 * @package mult1mate\crontab
 * @author mult1mate
 * Date: 20.12.15
 * Time: 18:49
 */
interface TaskRunInterface
{
    const RUN_STATUS_STARTED = 'started';
    const RUN_STATUS_COMPLETED = 'completed';
    const RUN_STATUS_ERROR = 'error';

    /**
     * Saves the task run
     * @return mixed
     */
    public function saveTaskRun();

    /**
     * @return int
     */
    public function getTaskRunId();

    /**
     * @return int
     */
    public function getTaskId();

    /**
     * @param int $task_id
     */
    public function setTaskId($task_id);

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
    public function getExecutionTime();

    /**
     * @param string $execution_time
     */
    public function setExecutionTime($execution_time);

    /**
     * @return string
     */
    public function getTs();

    /**
     * @param string $ts
     */
    public function setTs($ts);

    /**
     * @return string
     */
    public function getOutput();

    /**
     * @param string $output
     */
    public function setOutput($output);
}
