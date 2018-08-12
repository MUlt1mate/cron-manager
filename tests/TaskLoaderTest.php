<?php
namespace mult1mate\crontab_tests;

use mult1mate\crontab\TaskLoader;

/**
 * @author mult1mate
 * Date: 07.02.16
 * Time: 13:49
 */
class TaskLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSetClassFolder()
    {
        $set = TaskLoader::setClassFolder(__DIR__);
        $this->assertTrue(is_array($set));
    }

    /**
     * @throws \mult1mate\crontab\TaskManagerException
     */
    public function testGetAllMethods()
    {
        $result = TaskLoader::getAllMethods(
            array(__DIR__ . '/..', __DIR__, __DIR__ . '/correct_mocks'),
            array(null, 'mult1mate\\crontab_tests\\')
        );
        $this->assertTrue(is_array($result));
    }

    /**
     * @throws \mult1mate\crontab\TaskManagerException
     */
    public function testGetAllMethodsExceptions()
    {
        $this->setExpectedException('mult1mate\crontab\TaskManagerException');
        TaskLoader::getAllMethods('/mocks/');
    }

    /**
     * @throws \mult1mate\crontab\TaskManagerException
     */
    public function testGetControllerMethodsExceptions()
    {
        $this->setExpectedException('mult1mate\crontab\TaskManagerException');
        TaskLoader::getControllerMethods('/mocks/');
    }

    /**
     * @throws \mult1mate\crontab\TaskManagerException
     */
    public function testLoadControllerExceptionsFile()
    {
        $this->setExpectedException('mult1mate\crontab\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__ . '/wrong_mocks');
        TaskLoader::loadController('FileWithoutClass');
    }

    /**
     * @throws \mult1mate\crontab\TaskManagerException
     */
    public function testLoadControllerExceptions()
    {
        $this->setExpectedException('mult1mate\crontab\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__);
        TaskLoader::loadController('MockClass');
    }
}
