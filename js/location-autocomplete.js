$(function() {
    $('#location-input').autoComplete({
        minLength: 2,
        resolver: 'custom',
        events: {
            search: function(qry, callback) {
                $.ajax(
                    'include/search-location.inc.php', {
                        data: { qry }
                    }
                ).done(function(res) {
                    var arr = JSON.parse(res);
                    var newResult = [];
                    arr.forEach(element => {
                        newResult.push({
                            'id': element.id,
                            'text': element.value
                        });
                    });
                    callback(newResult)
                });
            },
        }
    });

    //If user not pick up value - remove value for input text box
    $('#location-input').on('change', function(evt, value) {
        $('#location-input').val('');
    });


    $('#location-input').on('autocomplete.select', function(evt, item) {
        $("#location-id").val(item.id);
        $("#location-input").val(item.text);
    });
});