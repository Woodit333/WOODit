$(function() {
    $('#modalImage').on('show.bs.modal', function(e) {
        var src = $(e.relatedTarget).attr('src');
        $('#modalImageElement').attr('src', src);
    });
});

function loadFile(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    $(".alert-danger").remove();
    $('.custom-file-label').text(event.target.files[0].name)
};

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});