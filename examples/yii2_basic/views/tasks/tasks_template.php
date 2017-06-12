<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:29
 * @var $content
 */
$menu = array(
    'index' => 'Tasks list',
    'task-edit' => 'Add new/edit task',
    'task-log' => 'Logs',
    'export' => 'Import/Export',
    'tasks-report' => 'Report',
);
?>
<script src="manager_actions.js"></script>
<div class="col-lg-10">
    <h2>Cron tasks manager</h2>

    <ul class="nav nav-tabs">
        <?php foreach ($menu as $m => $text):
            $class = (isset($_GET['m']) && ($_GET['m'] == $m)) ? 'active' : '';
            ?>
            <li class="<?= $class ?>"><a href="?r=tasks/<?= $m ?>"><?= $text ?></a></li>
        <?php endforeach; ?>
    </ul>
    <br>
</div>
