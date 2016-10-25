<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:38
 * @var array $tasks
 * @var array $methods
 */
$this->load->view('tasks/template');
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
         * @var Task $t
         */
        $status_class = (\mult1mate\crontab\TaskInterface::TASK_STATUS_ACTIVE == $t->status) ? '' : 'text-danger';
        ?>
        <tr>
            <td>
                <input type="checkbox" value="<?= $t->task_id ?>" class="task_checkbox">
            </td>
            <td><?= $t->task_id ?></td>
            <td><?= $t->time ?></td>
            <td><?= $t->command ?></td>
            <td class="<?= $status_class ?>"><?= $t->status ?></td>
            <td><?= $t->comment ?></td>
            <td><?= $t->ts ?></td>
            <td><?= $t->ts_updated ?></td>
            <td>
                <a href="<?php echo site_url('TasksController/taskEdit') ?>?task_id=<?= $t->task_id ?>">Edit</a>
            </td>
            <td>
                <a href="<?php echo site_url('TasksController/taskLog') ?>?task_id=<?= $t->task_id ?>">Log</a>
            </td>
            <td>
                <a href="#" class="run_task"
                   data-task-id="<?php echo $t->task_id ?>"
                   data-controller="<?php echo site_url('TasksController'); ?>">Run</a>
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
        <input type="hidden" id="controller" name="controller" value="<?php echo site_url('TasksController'); ?>" />
    </div>
    <input type="submit" value="Run" class="btn btn-primary" id="run_custom_task">
</form>
<div id="output_section" style="display: none;">
    <h3>Task output</h3>
    <pre id="task_output_container"></pre>
</div>
