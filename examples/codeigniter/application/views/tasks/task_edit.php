<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:56
 * @var Task $task
 * @var array $methods
 */
$this->load->view('tasks/template');
?>
<form method="post">
    <div class="col-lg-6">
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
                   value="<?= $task->command ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" id="status">
                <option value="active">Active</option>
                <option value="inactive"<?php if ('inactive' == $task->status) echo ' selected' ?>>Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <input type="text" class="form-control" id="comment" name="comment" value="<?= $task->comment ?>">
            <input type="hidden" id="controller" name="controller" value="<?php echo site_url('TasksController'); ?>" />
        </div>

        <?php if ($task->task_id): ?>
            <input type="hidden" name="task_id" value="<?= $task->task_id ?>">
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Save</button>

    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="times">Predefined intervals</label>
            <select class="form-control" id="times" style="width: 200px;">
                <option></option>
                <option value="* * * * *">Minutely</option>
                <option value="0 * * * *">Hourly</option>
                <option value="0 0 * * *">Daily</option>
                <option value="0 0 * * 0">Weekly</option>
                <option value="0 0 1 * *">Monthly</option>
                <option value="0 0 1 1 *">Yearly</option>
            </select>
        </div>
        <div class="form-group">
            <label for="time">Time</label>
            <input type="text" class="form-control" id="time" name="time" placeholder="* * * * *"
                   value="<?= $task->time ?>" style="width: 200px;" required>
        </div>
    <pre>
*    *    *    *    *
-    -    -    -    -
|    |    |    |    |
|    |    |    |    |
|    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
|    |    |    +---------- month (1 - 12)
|    |    +--------------- day of month (1 - 31)
|    +-------------------- hour (0 - 23)
+------------------------- min (0 - 59)
    </pre>
        <h4>Next runs</h4>
        <div id="dates_list"></div>
    </div>
</form>
