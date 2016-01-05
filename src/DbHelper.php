<?php
/**
 * User: mult1mate
 * Date: 05.01.16
 * Time: 18:08
 */

namespace mult1mate\crontab;


class DbHelper
{
    public static function getReportSql()
    {
        return "
        SELECT t.command,
        SUM(CASE WHEN tr.status = 'started' THEN 1 ELSE 0 END) AS started,
        SUM(CASE WHEN tr.status = 'completed' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN tr.status = 'error' THEN 1 ELSE 0 END) AS error,
        count(*) AS runs
        FROM task_runs AS tr
        LEFT JOIN tasks AS t ON t.task_id=tr.task_id
        WHERE tr.ts BETWEEN ? AND ? + INTERVAL 1 DAY
        GROUP BY command
        ORDER BY tr.task_id";
    }
}