<?php
/**
 * User: mult1mate
 * Date: 21.12.15
 * Time: 0:29
 */
$menu = [
    'index' => 'Tasks list',
    'checkTasks' => 'Check and run tasks',
    'addTask' => 'Add new task',
];
?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
      integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
      integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<div class="col-lg-10">
    <h2>Crontab manager</h2>
    <h3>ActiveRecord example</h3>

    <ul class="nav nav-tabs">
        <?php foreach ($menu as $m => $text):
            $class = (isset($_GET['m']) && ($_GET['m'] == $m)) ? 'active' : '';
            ?>
            <li class="<?= $class ?>"><a href="?m=<?= $m ?>"><?= $text ?></a></li>
        <?php endforeach; ?>
    </ul>
    <br>
