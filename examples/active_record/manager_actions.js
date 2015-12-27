$(function () {
    $('.run_task').click(function () {
        if (confirm('Are you sure?')) {
            $('#output_section').show();
            $('#task_output_container').text('Running...');
            $.post('?m=runTask', {task_id: $(this).attr('href')}, function (data) {
                $('#task_output_container').html(data);
            })
        }
        return false;
    });
    $('#method').change(function () {
        $('#command').val($(this).val());
    });
    $('.show_output').click(function () {
        $.post('?m=getOutput', {task_run_id: $(this).attr('href')}, function (data) {
            //if you want to render html use .html(data) instead of .text(data)
            $('#output_container').html(data);
            return false;
        })
    });
    $('#run_custom_task').click(function () {
        if (confirm('Are you sure?')) {
            $('#output_section').show();
            $('#task_output_container').text('Running...');
            $.post('?m=runTask', {custom_task: $('#command').val()}, function (data) {
                $('#task_output_container').html(data);
            })
        }
        return false;
    });
    function getRunDates() {
        $.post('?m=getDates', {time: $('#time').val()}, function (data) {
            $('#dates_list').html(data);
        })
    }

    $('#time').change(function () {
        getRunDates();
    });
    if ($('#time').length)
        getRunDates();

    $('#times').change(function () {
        $('#time').val($(this).val());
        getRunDates();
    });
});