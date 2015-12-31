$(function () {
    //tasks list page
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
    $('#select_all').change(function () {
        if ($(this).prop('checked'))
            $('.task_checkbox').prop('checked', 'checked');
        else
            $('.task_checkbox').prop('checked', '');
    });
    $('#execute_action').click(function () {
        var action = $('#action').find('option:selected').val();
        var tasks = $('.task_checkbox:checked').map(function () {
            return $(this).val();
        }).get();
        if ('Run' == action) {
            if (confirm('Are you sure?')) {
                $('#output_section').show();
                $('#task_output_container').text('Running...');
                $.post('?m=runTask', {task_id: tasks}, function (data) {
                    $('#task_output_container').html(data);
                })
            }
        } else {
            $.post('?m=tasksUpdate', {task_id: tasks, action: action}, function () {
                window.location.reload();
            });
        }
        return false;
    });
    $('.show_output').click(function () {
        $.post('?m=getOutput', {task_run_id: $(this).attr('href')}, function (data) {
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

    //edit page
    $('#method').change(function () {
        $('#command').val($(this).val());
    });

    function getRunDates() {
        $.post('?m=getDates', {time: $('#time').val()}, function (data) {
            $('#dates_list').html(data);
        })
    }

    var $time = $('#time');
    $time.change(function () {
        getRunDates();
    });
    if ($time.length)
        getRunDates();

    $('#times').change(function () {
        $('#time').val($(this).val());
        getRunDates();
    });

    //export page
    $('#parse_crontab_form').submit(function () {
        $.post('?m=parseCrontab', $(this).serialize(), function (data) {
            var list = '';
            data.forEach(function (element) {
                element.forEach(function (el) {
                    list += '' + el + '<br>';
                });
                list += '<hr>';
            });
            $('#parse_result').html(list);
        }, 'json');
        return false;
    });
});