<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:38
 * @var array $tasks
 * @var array $methods
 */
?>
<table class="table table-bordered">
    <tr>
        <th>
            <input type="checkbox" id="select_all">
        </th>
        <th>ID</th>
        <th>Time</th>
        <th>Command</th>
        <th>Status</th>
        <th>Comment</th>
        <th>Created</th>
        <th>Updated</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php
    foreach ($tasks as $t):
        /**
         * @var \mult1mate\crontab\TaskInterface $t
         */
        $status_class = (\mult1mate\crontab\TaskInterface::TASK_STATUS_ACTIVE == $t->getStatus()) ? '' : 'text-danger';
        $ts =  $t->getTs() ;
        $ts_updated =$t->getTsUpdated() ;
        ?>
        <tr>
            <td>
                <input type="checkbox" value="<?= $t->getTaskId() ?>" class="task_checkbox">
            </td>
            <td><?= $t->getTaskId() ?></td>
            <td><?= $t->getTime() ?></td>
            <td><?= $t->getCommand() ?></td>
            <td class="<?= $status_class ?>"><?= $t->getStatus() ?></td>
            <td><?= $t->getComment() ?></td>
            <td><?= $ts ?></td>
            <td><?= $ts_updated ?></td>
            <td>
                <a href="?m=taskEdit&task_id=<?= $t->getTaskId() ?>">Edit</a>
            </td>
            <td>
                <a href="?m=taskLog&task_id=<?= $t->getTaskId() ?>">Log</a>
            </td>
            <td>
                <a href="<?= $t->getTaskId() ?>" class="run_task">Run</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<form class="form-inline">
    <div class="form-group">
        <label for="action">With selected</label>
        <select class="form-control" id="action">
            <option>Enable</option>
            <option>Disable</option>
            <option>Delete</option>
            <option>Run</option>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" value="Apply" class="btn btn-primary" id="execute_action">
    </div>
</form>
<form class="form-inline">
    <h3>Run custom task</h3>
    <div class="form-group">
        <label for="method">Methods</label>
        <select class="form-control" id="method">
            <option></option>
            <?php foreach ($methods as $class => $class_methods): ?>
                <optgroup label="<?= $class ?>">
                    <?php foreach ($class_methods as $m): ?>
                        <option value="<?= $class . '::' . $m . '()' ?>"><?= $m ?></option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="command">Command</label>
        <input type="text" class="form-control" id="command" name="command" placeholder="Controller::method"
               style="width: 300px;">
    </div>
    <input type="submit" value="Run" class="btn btn-primary" id="run_custom_task">
</form>
<div id="output_section" style="display: none;">
    <h3>Task output</h3>
    <pre id="task_output_container"></pre>
</div>
