<?php
use mult1mate\crontab\TaskManager;

/**
 * User: mult1mate
 * Date: 01.02.16
 * Time: 0:48
 */
class TaskManagerTest extends PHPUnit_Framework_TestCase
{
    public function testValidateCommand()
    {
//        require_once __DIR__ . '/../vendor/autoload.php';
        $result = TaskManager::validateCommand('Class::method( arg1 , arg2 ) ');
        $this->assertEquals($result, 'Class::method(arg1,arg2)');

        $result = TaskManager::validateCommand('Class->method( arg1 , arg2 ) ');
        $this->assertFalse($result);
    }
}
