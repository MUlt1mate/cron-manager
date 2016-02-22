<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 1:13
 * @var array $runs
 */
echo $this->render('tasks_template');
$this->title = 'Task Manager - Run list';
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Task ID</th>
        <th>Command</th>
        <th>Status</th>
        <th>Time</th>
        <th>Started</th>
        <th></th>
    </tr>
    <?php foreach ($runs as $r):
        /**
         * @var \app\models\TaskRun $r
         */
        ?>
        <tr>
            <td><?= $r['task_run_id'] ?></td>
            <td><?= $r['task_id'] ?> </td>
            <td><?= $r['command'] ?></td>
            <td><?= $r['status'] ?></td>
            <td><?= $r['execution_time'] ?></td>
            <td><?= $r['ts'] ?></td>
            <td>
                <?php if (!empty($r['output'])): ?>
                    <a href="<?= $r['task_run_id'] ?>" data-toggle="modal" data-target="#output_modal"
                       class="show_output">Show output</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<div class="modal fade" tabindex="-1" role="dialog" id="output_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Task run output</h4>
            </div>
            <div class="modal-body">
                <pre id="output_container">Loading...</pre>
            </div>
        </div>
    </div>
</div>
