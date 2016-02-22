<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:56
 * @var \app\models\Task $task
 * @var array $methods
 */
use yii\bootstrap\ActiveForm;

echo $this->render('tasks_template');
$this->title = 'Task Manager - Edit task';
$form = ActiveForm::begin([]);
?>
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
        <?= $form->field($task, 'command')->textInput(['placeholder' => 'Controller::method']) ?>
        <?= $form->field($task, 'status')->dropDownList(array(
            \mult1mate\crontab\TaskInterface::TASK_STATUS_ACTIVE => 'Active',
            \mult1mate\crontab\TaskInterface::TASK_STATUS_INACTIVE => 'Inactive',
            \mult1mate\crontab\TaskInterface::TASK_STATUS_DELETED => 'Deleted',
        )) ?>
        <?= $form->field($task, 'comment') ?>

        <button type="submit" class="btn btn-primary">Save</button>

    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="times">Predefined intervals</label>
            <select class="form-control" id="times">
                <option></option>
                <option value="* * * * *">Minutely</option>
                <option value="0 * * * *">Hourly</option>
                <option value="0 0 * * *">Daily</option>
                <option value="0 0 * * 0">Weekly</option>
                <option value="0 0 1 * *">Monthly</option>
                <option value="0 0 1 1 *">Yearly</option>
            </select>
        </div>
        <?= $form->field($task, 'time')->textInput(['placeholder' => '* * * * *']) ?>
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
<?php
ActiveForm::end();
