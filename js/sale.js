$(function() {
    //On sale modal open 
    $('#saleModal').on('show.bs.modal', function(e) {
        var modalCaller = $(e.relatedTarget),
            productId = modalCaller[0].id,
            jqXHR = $.ajax({
                url: 'include\\cart-ajax.inc.php',
                type: 'get',
                data: "productId=" + productId + "&action=get",
                dataType: 'json'
            });

        //fill modal with product data
        jqXHR.done(function(data, textStatus, jqXHR) {
            var carousel = $('.carousel-inner'),
                carouselIndicators = $('.carousel-indicators'),
                priceText = '',
                price = data.price,
                discountPrice = 0;

            $('#saleModalTitle').text(data.name);
            $('#productId').val(data.id);

            if (data.discount_percentage > 0) {
                discountPrice = price - (price * (data.discount_percentage / 100));
                 priceText = '<span class="sale-price"><strong>' + discountPrice + '</strong><small>₪</small></span>';
                priceText += '<span class="old-price">' + price + '<small>₪</small></span>';
            } else {
                priceText = '<span class="sale-price"><strong>' + price + '</strong><small>₪</small></span>';
            }
            $('.price-wrapper').append(priceText);

            for (let index = 0; index < data.images.length; index++) {
                const image = data.images[index].image;
                let active = '';
                if (index == 0) {
                    active = 'active';
                }
                carousel.append('<div class="carousel-item ' + active + '"><img class="d-block w-100" src="uploads\\' + image + '"></div>')
                carouselIndicators.append('<li data-target="#carousel-thumb" data-slide-to="' + index + '" class="' + active + '"><img class="d-block w-100" src="uploads\\' + image + '" class="img-fluid"></li>');
            }
            console.log(data);
        });

    });

    //On sale modal close - clear modal data 
    $("#saleModal").on("hidden.bs.modal", function(e) {
        $('#saleModalTitle').text('');
        $('#productId').val('');
        $('.price-wrapper').empty();
        $('.carousel-inner').empty();
        $('.carousel-indicators').empty();
        $('#output').attr('src', '');
        $(".alert-danger").remove();
    });
    
    $('#saleToast').on('shown.bs.toast', function () {
        $('#polite').show();
    });
    
    $('#saleToast').on('hidden.bs.toast', function () {
        $('#polite').hide();
    });

    $(".fa-close").click(function() {
        var $item = $(this),
            productId = $item.closest(".row")[0].id ,
            jqXHR = $.ajax({
                        url: 'include\\cart-ajax.inc.php',
                        type: 'get',
                        data: "id=" + productId + "&action=remove",
                        dataType: 'json'
                    });
          
        jqXHR.done(function(data, textStatus, jqXHR) {
            location.reload();
        });

    });
});

$(document).ready(function(e) {
    $("form").on('submit', (function(e) {
        e.preventDefault();

        var fd = new FormData(),
            // for multiple files
            file_data = $('input[type="file"]')[0].files,
            modalBody = $(".modal-body");

        // if user not upload image
        if (file_data.length === 0) {
            if ($(".alert-danger").length === 0) {
                modalBody.append('<div class="alert alert-danger" role="alert">כדי להוסיף לעגלה, יש לבחור תמונה</div>');
            }
            return;
        }
        fd.append("image", file_data[0]);
        fd.append('productId', $('#productId').val());

        $.ajax({
            url: "include/cart-ajax.inc.php",
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                //close the modal
                $('#saleModal').modal('hide');
                $('.toast').toast('show');
            },
            success: function(data) {

            },
            error: function(e) {
                $("#err").html(e).fadeIn();
            }
        });
    }));
});
