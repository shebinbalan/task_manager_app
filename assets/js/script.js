$(document).ready(function() {
    // AJAX for task status update
    $('.task-status-checkbox').change(function() {
        const checkbox = $(this); // Preserve reference
        const taskId = checkbox.data('task-id');
        const isChecked = checkbox.is(':checked');
        const status = isChecked ? 'Completed' : 'Pending';
        const taskCard = checkbox.closest('.task-card');

        $.ajax({
            url: 'task_actions.php',
            type: 'POST',
            data: {
                action: 'update_status',
                task_id: taskId,
                status: status
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    if (isChecked) {
                        taskCard.addClass('completed');
                        taskCard.find('.status-badge')
                            .removeClass('bg-warning bg-primary')
                            .addClass('bg-success')
                            .text('Completed');
                    } else {
                        taskCard.removeClass('completed');
                        taskCard.find('.status-badge')
                            .removeClass('bg-success')
                            .addClass('bg-warning')
                            .text('Pending');
                    }
                } else {
                    alert('Error updating task status: ' + result.message);
                    checkbox.prop('checked', !isChecked);
                }
            },
            error: function() {
                alert('An error occurred while updating the task.');
                checkbox.prop('checked', !isChecked);
            }
        });
    });

    // Optional: Let filter form submit as normal
    $('#filter-form').on('submit', function(e) {
        // no need to prevent default
    });
});