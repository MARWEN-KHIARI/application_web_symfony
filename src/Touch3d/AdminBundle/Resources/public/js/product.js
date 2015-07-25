function deleteProduct(id1) {
    $("#loading").fadeIn();
    $.post(_deleteProduct, {
        id: id1
    },function (data) {
        afficheMess(data);
        ReloadPage();//location.reload();
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    return false;
}


function viewProduct(id1) {
    $("#loading").fadeIn();
    $.post(_selectXProduct, {
        id: id1
    },function (data) {
        bootbox.alert(data, "Products Details");
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
    e.preventDefault();
    return false;
}

function saveProduct(id1) {
    $("#loading").fadeIn();
    var outContent = tinymce.activeEditor.getContent();
    //var outContent = $("#touch3d_adminbundle_produit_contenu").val();
    var urlSaveProduct = _editProduct;
    if (id1 == null) {
        urlSaveProduct = _newProduct;
        id1 = -1;
    }
    $.post(urlSaveProduct, {
        id: id1,
        nom: $("#touch3d_adminbundle_produit_nom").val(),
        img: $("#touch3d_adminbundle_produit_img").val(),
        fournisseur: $("#touch3d_adminbundle_produit_fournisseur").val(),
        qteStock: $("#touch3d_adminbundle_produit_qteStock").val(),
        prix: $("#touch3d_adminbundle_produit_prix").val(),
        resumer: $("#touch3d_adminbundle_produit_resumer").val(),
        contenu: outContent,
        status: $("#touch3d_adminbundle_produit_status").val(),
        categorie: $("#touch3d_adminbundle_produit_categorie").val()
    },function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
        //location.reload();
    });
    //e.preventDefault();
    return false;
}


function saveEditProduct(id1) {
    $("#loading").fadeIn();
    var outContent = tinymce.activeEditor.getContent();
    //var outContent = $("#touch3d_adminbundle_produit_contenu").val();
    var urlSaveProduct = _editProduct;
    if (id1 == null) {
        urlSaveProduct = _newProduct;
        id1 = -1;
    }

$.post(urlSaveProduct,
    $('#form1').serialize()
    ,function (data) {
        afficheMess(data);
    }, 'html').always(function () {
        $("#loading").fadeOut();
    });
return false;
}


function findProduct() {
    $("#loading").fadeIn();
    $.post(_findProduct, {
        req: $("#searchText").val()
    },function (data) {
        $("#tableProducts").empty();
        $('#tableProducts').html(data).hide();
    }, 'text').always(function () {
        $('#tableProducts').fadeIn("slow");
        $("#pagination").html("");
        $("#loading").fadeOut();
    });
    return false;
}

function viewAllProduct(page) {
    reset();
    $("#loading").fadeIn();
    statistique();
    page = GetPage(page);
    $.post(_viewPaginatedProduct, {
        pageActive: page
    },function (data) {
        $("#tableProducts").empty();
        $('#tableProducts').html(data).hide();
    }, 'text').always(function () {
        $('#tableProducts').fadeIn("slow");
        pagination(page);
    });
    //e.preventDefault();
    return false;
}

function statistique() {
    $("#info_canvas_product").load(_statisticProduct).hide().fadeIn();
}

function pagination(page) {
    $.post(_paginateProduct, { pageActive: page },function (data) {
        $("#pagination").html(data);
    }, 'text').always(function () {
        $("#loading").fadeOut("slow");
        scrollToBottomx();
    });
}

function GetPage(page) {
    if (page == null) {
        var ck = readCookie('AdminPage');
        if (ck) {
            page = ck;
        } else {
            page = 1;
        }
    } else {
        createCookie('AdminPage', page, 2);
    }
    return page;
}

function reset() {
    document.getElementById('searchText').value = "";
}


function ReloadPage() {
    // var timer = setInterval("ReloadPage()", 1000);
    viewAllProduct();
}

