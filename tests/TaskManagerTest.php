<?php
namespace mult1mate\crontab_tests;

use mult1mate\crontab\TaskInterface;
use mult1mate\crontab\TaskManager;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 0:48
 */
class TaskManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testEditTask()
    {
        $task = TaskMock::createNew();
        $command = 'ActionMock::method()';
        $task = TaskManager::editTask($task, '* * * * *', $command, TaskInterface::TASK_STATUS_ACTIVE, 'comment');
        $this->assertEquals($command, $task->getCommand());

        $command = 'wrong_command';
        $task = TaskManager::editTask($task, '* * * * *', $command, TaskInterface::TASK_STATUS_ACTIVE, 'comment');
        $this->assertNotEquals($command, $task->getCommand());
    }

    public function testValidateCommand()
    {
        $result = TaskManager::validateCommand('Class::method( arg1 , arg2 ) ');
        $this->assertEquals($result, 'Class::method(arg1,arg2)');

        $result = TaskManager::validateCommand('Class->method( arg1 , arg2 ) ');
        $this->assertFalse($result);
    }

    public function testParseCrontab()
    {
        $task = TaskMock::createNew();
        $cron = '
        #comment
        * * * * * cd path/; /usr/bin/php index.php controller method args 2>&1 > /dev/null
        * * * * -1 cd path/; /usr/bin/php index.php controller method args 2>&1 > /dev/null
        * * * * * cd path/; wrong expression';
        TaskManager::parseCrontab($cron, $task);
    }

    public function testGetTaskCrontabLine()
    {
        $task = TaskMock::createNew();
        $task->setStatus(TaskInterface::TASK_STATUS_INACTIVE);
        $task->setCommand('Class::method()');
        $task->setComment('comment');
        $task->setTime('* * * * *');
        $export = TaskManager::getTaskCrontabLine($task, 'path', 'php', 'index.php');
        $this->assertEquals("#comment\n#* * * * * cd path; php index.php Class method  2>&1 > /dev/null\n", $export);
    }
}
