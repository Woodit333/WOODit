$('#my-table').Tabledit({
    url: 'include\\inventory-ajax.inc.php',
    columns: {
        identifier: [0, 'id'],
        editable: [
            [1, 'name'],
            [2, 'quantity_available'],
            [3, 'quantity_needs']
        ]
    },
    restoreButton: false,
    deleteButton: true
});