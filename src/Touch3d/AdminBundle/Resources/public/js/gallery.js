//var _deleteImages = UrlRoot+"app_dev.php/"+"admin/dashboard/gallery/ajax/_deleteImages";
var dividerCatalogueResolution = 1;
function GetDividerCatalogueResolution() {
    if (SIZE == "LARGE") {
        dividerCatalogueResolution = dividerCatalogueResolution_.LARGE.value;
    } else if (SIZE == "MEDIUM") {
        dividerCatalogueResolution = dividerCatalogueResolution_.MEDIUM.value;
    } else if (SIZE == "SMALL") {
        dividerCatalogueResolution = dividerCatalogueResolution_.SMALL.value;
    }
}
$(document).ready(function () {
    dividerCatalogue();
});

function onResizeFunction() {
    dividerCatalogue();
}
function dividerCatalogue() {
    $("#catalogueImg").html("");
    $("#catalogueImg").fadeOut();
    $("#catalogueImg").html(catalogueImg);
    GetDividerCatalogueResolution();
    if (imgsId.length > dividerCatalogueResolution){
    for (var i = 2; i < imgsId.length; i++) {
        if ((i % dividerCatalogueResolution) == 0) {
            $("#blockImg_" + imgsId[i]).before("<br/>");
        }
    }
    }
    $("#catalogueImg").fadeIn();
}



function deleteImages() {
    $("#loading").fadeIn();
    var Id2delete = new Array();
    for (var i = 0; i < imgsId.length; i++) {
        if ($("#image_" + imgsId[i]).is(':checked')) {
            $("#image_" + imgsId[i]).prop('checked', false);
            $("#blockImg_" + imgsId[i]).fadeOut();
            Id2delete.push(imgsId[i]);
        }
    }
    deleteImagesBD(Id2delete);
    for (i = 0; i < Id2delete.length; i++) {
        imgsId.splice(imgsId.indexOf(Id2delete[i]), 1);
    }
    //console.log(imgsId);
}

function deleteImagesBD(Id2delete) {
    $.post(_deleteImages, {
        Id2delete: Id2delete
    },function (data) {
        afficheMess(data);
        //ReloadPage();
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });

    return false;
}


function resetTag() {
    document.getElementById('touch3d_adminbundle_imagetype_alt').value = "";
    document.getElementById('touch3d_adminbundle_imagetype_legend').value = "";
}



/*
 var admin_ajax_upload_Pic = UrlRoot+"app_dev.php/admin/ajax/uploadImage";
 $('#form_pic').submit(function () {
 var data = $('#form_pic').serialize();
 $.ajax({
 url: admin_ajax_upload_Pic,
 type: 'POST',
 data: data,
 enctype: 'multipart/form-data',
 success: function (response) {
 afficheMess(response);
 resetTag();
 // location.reload();
 //alert(response);
 }
 });
 e.preventDefault();
 return false;
 });
 */