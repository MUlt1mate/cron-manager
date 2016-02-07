<?php
namespace mult1mate\crontab_tests;

use mult1mate\crontab\DbHelper;

/**
 * @author mult1mate
 * Date: 07.02.16
 * Time: 18:17
 */
class DbHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReportSql()
    {
        $sql = DbHelper::getReportSql();
        $this->assertTrue(is_string($sql));
    }

    public function testTableTasksSql()
    {
        $sql = DbHelper::tableTasksSql();
        $this->assertTrue(is_string($sql));
    }

    public function testTableTaskRunsSql()
    {
        $sql = DbHelper::tableTaskRunsSql();
        $this->assertTrue(is_string($sql));
    }
}
