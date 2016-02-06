<?php
/**
 * @author mult1mate
 * Date: 31.12.15
 * Time: 13:35
 */
echo $this->render('tasks_template');
?>
<div class="col-lg-6">
    <h2>Import</h2>
    <form method="post" id="parse_crontab_form">
        <div class="form-group">
            <label for="crontab">Paste crontab content</label>
            <textarea class="form-control" name="crontab" id="crontab"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Parse" class="btn btn-primary">
        </div>
    </form>
    <div id="parse_result">
    </div>
</div>

<div class="col-lg-6">
    <h2>Export</h2>
    <form class="form-inline" id="export_form">
        <div class="form-group">
            <label class="control-label" for="php">Path to PHP</label>
            <input type="text" class="form-control" name="php" id="php" value="/usr/bin/php" style="width: 100px;">
        </div>
        <div class="form-group">
            <label class="control-label" for="folder">Path to folder</label>
            <input type="text" class="form-control" name="folder" id="folder" value="/home/project/">
        </div>
        <div class="form-group">
            <label class="control-label" for="file">php file</label>
            <input type="text" class="form-control" name="file" id="file" value="index.php" style="width: 100px;">
        </div>
        <div class="form-group">
            <input type="submit" value="Export" class="btn btn-primary">
        </div>
    </form>
    <code id="export_result"></code>
</div>