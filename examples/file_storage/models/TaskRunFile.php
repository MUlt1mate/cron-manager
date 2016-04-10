<?php
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use mult1mate\crontab\TaskRunInterface;

/**
 * This model saves task run results into log files
 * @author mult1mate
 * Date: 10.04.16
 * Time: 13:39
 */
class TaskRunFile implements TaskRunInterface
{
    protected $task_id;
    protected $status;
    protected $execution_time;
    protected $output;
    protected $ts;

    /**
     * Folder with logs files. Should exists
     * @var string
     */
    protected $logs_folder = '/tmp/crontasks_logs/';

    /**
     * Default log file name
     * @var string
     */
    protected $log_name = 'cron_log.log';

    /**
     * Writes log in file. Do NOT actually saves the task run
     * @return bool
     */
    public function saveTaskRun()
    {
        //if monolog not found does nothing
        if (!class_exists('Monolog\Logger')) {
            return false;
        }
        $logger = new Logger('cron_logger');
        $logger->pushHandler(new RotatingFileHandler($this->logs_folder . $this->log_name));
        $task = TaskFile::taskGet($this->task_id);
        if (self::RUN_STATUS_STARTED == $this->status) {
            $message = 'task ' . $task->getCommand() . ' just started';
        } else {
            $message = 'task ' . $task->getCommand() . ' ended with status ' . $this->status
                . ', execution time ' . $this->execution_time . ', output: ' . PHP_EOL . $this->output;
        }
        return $logger->addNotice($message);
    }

    /**
     * @return int
     */
    public function getTaskRunId()
    {
        return 0;
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

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
