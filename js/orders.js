$(function() {
     //On order modal open 
    $('#orderModal').on('show.bs.modal', function(e) {
        var modalCaller = $(e.relatedTarget),
            orderId = modalCaller[0].id,
            jqXHR = $.ajax({
                url: 'include\\orders-ajax.inc.php',
                type: 'get',
                data: "orderId=" + orderId + "&action=get",
                dataType: 'json'
            });

        //fill modal with product data
        jqXHR.done(function(data, textStatus, jqXHR) {
           var userData = 
            '<table class=my-table">\n\
                <tr><td><strong>ID:</strong></td><td>' + data.user.id +'</td></tr>\n\
                <tr><td><strong>שם:</strong></td><td>' + data.user.name +'</td></tr>\n\
                <tr><td><strong>כתובת:</strong></td><td>' + data.user.address +'</td></tr>\n\
                <tr><td><strong>אימייל:</strong></td><td>' + data.user.email +'</td></tr>\n\
            </table>';
        
            $('#orderModalTitle').text('מספר הזמנה: ' + data.id);
            $('.user-wrapper').append(userData);
            
            for (var i = 0; i < data.orderProducts.length; i++) {
                var p = 
                '<div class="col-md-6">\n\
                    <div class="card">\n\
                        <div class="card-header">\n\
                            ' + data.orderProducts[i].name + '\n\
                        </div>\n\
                        <img src="uploads/'+ data.orderProducts[i].image +'" class="in-modal card-img-top">\n\
                        <div class="card-footer">\n\
                            ' + data.orderProducts[i].price +'₪\n\
                        </div>\n\
                    </div>\n\
                </div>';          
                $('.order-products').append(p);
            }
            console.log(data);
        });

    });

    //On order modal close - clear modal data 
    $("#orderModal").on("hidden.bs.modal", function() {
        $('#orderModalTitle').text('');
        $('.user-wrapper').empty();
        $('.order-products').empty();
    });
    
    $(".update-order").click(function() {
        var $item = $(this),
            orderId = $item[0].id,
            status =  $(this).closest('.order-row').find('.custom-select option:selected').val();
        
        var $item = $(this),
            jqXHR = $.ajax({
                        url: 'include\\orders-ajax.inc.php',
                        type: 'get',
                        data: "orderId=" + orderId + "&status=" + status + "&action=set",
                        dataType: 'json'
                    });
          
        jqXHR.done(function(data, textStatus, jqXHR) {
            location.reload();
        });

    });
});