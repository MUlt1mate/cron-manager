<?php
use mult1mate\crontab\TaskManager;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 0:48
 */
class TaskManagerTest extends PHPUnit_Framework_TestCase
{
    public function testValidateCommand()
    {
        $result = TaskManager::validateCommand('Class::method( arg1 , arg2 ) ');
        $this->assertEquals($result, 'Class::method(arg1,arg2)');

        $result = TaskManager::validateCommand('Class->method( arg1 , arg2 ) ');
        $this->assertFalse($result);
    }
}
