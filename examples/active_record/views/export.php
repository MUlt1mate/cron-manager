<?php
/**
 * User: mult1mate
 * Date: 31.12.15
 * Time: 13:35
 */
?>
<div class="col-lg-6">
    <form method="post" id="parse_crontab_form">
        <div class="form-group">
            <label for="crontab">Paste crontab</label>
            <textarea class="form-control" name="crontab" id="crontab"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Parse" class="btn btn-primary">
        </div>
    </form>
</div>
<div class="col-lg-6">
    <div id="parse_result">
    </div>
</div>