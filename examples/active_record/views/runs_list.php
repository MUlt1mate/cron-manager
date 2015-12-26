<?php
/**
 * User: mult1mate
 * Date: 21.12.15
 * Time: 1:13
 * @var array $runs
 */
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Task ID</th>
        <th>Command</th>
        <th>Status</th>
        <th>Time</th>
        <th>Ts</th>
    </tr>
    <?php foreach ($runs as $r):
        /**
         * @var TaskRun $r
         */
        ?>
        <tr>
            <td><?= $r->task_run_id ?></td>
            <td><?= $r->task->task_id ?> </td>
            <td><?= $r->task->command ?></td>
            <td><?= $r->status ?></td>
            <td><?= $r->execution_time ?></td>
            <td><?= $r->ts->format('Y-m-d H:i:s') ?></td>
        </tr>
    <?php endforeach; ?>
</table>
