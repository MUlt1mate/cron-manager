<?php
/**
 * User: mult1mate
 * Date: 21.12.15
 * Time: 0:56
 * @var Task $task
 */
?>
<div class="col-lg-6">
    <form method="post">
        <div class="form-group">
            <label for="time">Time</label>
            <input type="text" class="form-control" id="time" name="time" placeholder="* * * * *"
                   value="<?= $task->time ?>">
        </div>
        <div class="form-group">
            <label for="command">Command</label>
            <input type="text" class="form-control" id="command" name="command" placeholder="Controller::method"
                   value="<?= $task->command ?>">
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
        </div>

        <?php if ($task->task_id): ?>
            <input type="hidden" name="task_id" value="<?= $task->task_id ?>">
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>