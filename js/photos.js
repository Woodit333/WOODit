$(".dropdown-item").click(function() {
    var $item = $(this),
        text = $item.text(),
        productId = $item[0].id;

    $('form').removeClass('invisible');
    $('#productId').val(productId);

    var jqXHR = $.ajax({
        url: 'include\\photos-ajax.inc.php',
        type: 'get',
        data: "productId=" + productId + "&action=get",
        dataType: 'json'
    });

    // DONE callback-manipulation function - success
    jqXHR.done(function(data, textStatus, jqXHR) {
        var $images = $('#images'),
            $mainImages = $('#mainIamge');
         
        $mainImages.empty(); 
        $images.empty();
        for (let index = 0; index < data.length; index++) {
            var image = data[index];
            if (image.is_main === "1") {
                $mainImages.append('<div class="col-4 m-0 mt-4"><img src="uploads\\' + image.image + '" class="img-fluid"></div>');
            } else {
                var imgCard = `
                    <div class="card">
                        <img id="{1}" src="uploads\\{0}" class="card-img-top">
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-success" onclick="makeMain(this)">Make Main Image</button>
                            <button type="button" class="btn btn-danger" onclick="deleteImage(this)">Delete</button>
                        </div>
                    </div>`;
                imgCard = imgCard.replace('{0}', image.image);
                imgCard = imgCard.replace('{1}', image.id);
                $images.append(imgCard);
            }
        }
    });
});

function makeMain(btn) {
    var imageId = $(btn).parent().parent().find('img').attr('src').replace('uploads\\', ''),
        productId = $('#productId').val(),
        jqXHR = $.ajax({
        url: 'include\\photos-ajax.inc.php',
        type: 'get',
        data: "imageId=" + imageId + "&productId=" + productId + "&action=main",
        dataType: 'json'
    });

    jqXHR.done(function(data, textStatus, jqXHR) {
        location.reload();
    });
}

function deleteImage(btn) {
    var imageId = $(btn).parent().parent().find('img').attr('id'),
        jqXHR = $.ajax({
        url: 'include\\photos-ajax.inc.php',
        type: 'get',
        data: "imageId=" + imageId + "&action=delete",
        dataType: 'json'
    });

    jqXHR.done(function(data, textStatus, jqXHR) {
        location.reload();
    });
}