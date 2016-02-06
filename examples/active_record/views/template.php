<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:29
 */
$menu = array(
    'index' => 'Tasks list',
    'taskEdit' => 'Add new/edit task',
    'taskLog' => 'Logs',
    'export' => 'Import/Export',
    'tasksReport' => 'Report',
);
?>
<head>
    <title>Cron tasks manager</title>
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
    <script src="manager_actions.js"></script>
</head>
<div class="col-lg-10">
    <h2>Cron tasks manager</h2>

    <ul class="nav nav-tabs">
        <?php foreach ($menu as $m => $text):
            $class = (isset($_GET['m']) && ($_GET['m'] == $m)) ? 'active' : '';
            ?>
            <li class="<?= $class ?>"><a href="?m=<?= $m ?>"><?= $text ?></a></li>
        <?php endforeach; ?>
    </ul>
    <br>
