
var selectedImageUrl = "";
var selectedImageId = "";
function getSelectedImage(href, id) {
    selectImageUrl = UrlRoot + href;
    selectedImageId = id;
    var data = "<img src='" + selectImageUrl + "'/>";
    //bootbox.alert(data, "Picture Selected");//window.setTimeout(function() { bootbox.hideAll(); }, 3000);
    bootbox.confirm(data, "Select other", "Good", function (result) {
        if (result) {
            document.getElementById(IdInputTypeUrlPicture).value = selectImageUrl;
            bootbox.hideAll();
        }
    }).find(".btn-primary").removeClass("btn-primary").addClass("btn-success");
}

function selectImages() {

    $("#loading").fadeIn();
    $.post(_selectImages,function (data) {
        var box=bootbox.alert(data, "Select your Picture");
        box.find('.modal-content').css({'background-color': '#fff','inline': '1','z-index': '0'});
        box.find(".btn-primary").removeClass("btn-primary");
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
}