$('#my-table').Tabledit({
    url: 'include\\products-ajax.inc.php',
    columns: {
        identifier: [0, 'id'],
        editable: [
            [1, 'name'],
            [2, 'description'],
            [3, 'price'],
            [4, 'discount_percentage']
        ]
    },
    deleteButton: false
});

$('#my-table').on('click', 'button.tabledit-active', function(event) {
    if (event.handled !== true) {

        // Stop, the default action of the event will not be triggered
        event.preventDefault();

        // Get table cell
        var $td = $(this).closest('td');
        // Get table row.
        var $tr = $td.parent('tr');
        var value = $tr.find('.tabledit-input.tabledit-identifier')[0].value;

        var data = 'id=' + value;
        data += '&action=' + 'delete';

        var jqXHR = $.ajax({
            url: 'include\\products-ajax.inc.php',
            type: 'post',
            data: data,
            dataType: 'json'
        });

        // DONE callback-manipulation function - success
        jqXHR.done(function(data, textStatus, jqXHR) {
            location.reload();
        });

        event.handled = true;
    }
});

$('#my-table').on('click', 'button.tabledit-not-active', function(event) {
    if (event.handled !== true) {

        // Stop, the default action of the event will not be triggered
        event.preventDefault();

        // Get table cell
        var $td = $(this).closest('td');
        // Get table row.
        var $tr = $td.parent('tr');
        var value = $tr.find('.tabledit-input.tabledit-identifier')[0].value;

        var data = 'id=' + value;
        data += '&action=' + 'restore';

        var jqXHR = $.ajax({
            url: 'include\\products-ajax.inc.php',
            type: 'post',
            data: data,
            dataType: 'json'
        });

        // DONE callback-manipulation function - success
        jqXHR.done(function(data, textStatus, jqXHR) {
            location.reload();
        });

        event.handled = true;
    }
});

$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function() {
    $('[data-toggle="popover"]').popover()
})