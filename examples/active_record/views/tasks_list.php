<?php
/**
 * User: mult1mate
 * Date: 21.12.15
 * Time: 0:38
 * @var array $tasks
 * @var array $methods
 */
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Time</th>
        <th>Command</th>
        <th>Status</th>
        <th>Comment</th>
        <th>Ts</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php
    foreach ($tasks as $t):
        /**
         * @var Task $t
         */
        ?>
        <tr>
            <td><?= $t->task_id ?></td>
            <td><?= $t->time ?></td>
            <td><?= $t->command ?></td>
            <td><?= $t->status ?></td>
            <td><?= $t->comment ?></td>
            <td><?= $t->ts->format('Y-m-d H:i:s') ?></td>
            <td>
                <a href="?m=taskEdit&task_id=<?= $t->task_id ?>">Edit</a>
            </td>
            <td>
                <a href="?m=taskLog&task_id=<?= $t->task_id ?>">Log</a>
            </td>
            <td>
                <a href="<?= $t->task_id ?>" class="run_task">Run</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<form class="form-inline">
    <h3>Run custom task</h3>
    <div class="form-group">
        <label for="method">Methods</label>
        <select class="form-control" id="method">
            <option></option>
            <? foreach ($methods as $class => $class_methods): ?>
                <optgroup label="<?= $class ?>">
                    <? foreach ($class_methods as $m): ?>
                        <option value="<?= $class . '::' . $m . '()' ?>"><?= $m ?></option>
                    <? endforeach; ?>
                </optgroup>
            <? endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="command">Command</label>
        <input type="text" class="form-control" id="command" name="command" placeholder="Controller::method" style="width: 300px;">
    </div>
    <input type="submit" value="Run" class="btn btn-primary" id="run_custom_task">
</form>
<div id="output_section" style="display: none;">
    <h3>Task output</h3>
    <div class="alert alert-info" id="task_output_container">
    </div>
</div>